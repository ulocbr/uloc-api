<?php

namespace Uloc\ApiBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ResponseFactory;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Uloc\ApiBundle\Services\JWT\TokenExtractor\AuthorizationHeaderTokenExtractor;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;
    private $responseFactory;

    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManagerInterface $em, ResponseFactory $responseFactory)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return false;
        }

        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        $username = $data['username'];

        $user = $this->em
            ->getRepository(User::class)
            ->findOneBy(['username' => $username, 'deleted' => false]);

        if(!method_exists($user, 'getRoles')){
            throw new CustomUserMessageAuthenticationException('Invalid User');
        }

        if( count($user->getRoles()) < 1 ){
            throw new CustomUserMessageAuthenticationException('Invalid Roles');
        }

        if (isset($_SERVER['USER_CLIENT'])) {
            $client = $data['client'] ?? null;
            if ($client !== $_SERVER['USER_CLIENT']) {
                throw new CustomUserMessageAuthenticationException(sprintf('Invalid User Client Session %s/%s', $client, $_SERVER['USER_CLIENT']));
            }
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $apiProblem = new ApiProblem(401);
        // you could translate this
        $apiProblem->set('detail', $exception->getMessageKey());

        return $this->responseFactory->createResponse($apiProblem);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // do nothing - let the controller be called
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // called when authentication info is missing from a
        // request that requires it

        $apiProblem = new ApiProblem(401);
        // you could translate this
        $message = $authException ? $authException->getMessageKey() : 'Missing credentials';
        $apiProblem->set('detail', $message);

        return $this->responseFactory->createResponse($apiProblem);
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }


}
