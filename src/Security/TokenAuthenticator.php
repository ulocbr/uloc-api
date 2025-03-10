<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Security;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ResponseFactory;
use Uloc\ApiBundle\Entity\ApiToken;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $responseFactory;

    public function __construct(EntityManagerInterface $em, ResponseFactory $responseFactory)
    {
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    public function getCredentials(Request $request)
    {
        /**
         * Extrai o token
         */
        if (!$request->headers->has('X-AUTH-TOKEN')) {
            return false;
        }
        $token = $request->headers->get('X-AUTH-TOKEN');

        if (empty($token)) return false;

        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->em->getRepository(ApiToken::class)->findBy([
                'token' => $credentials
            ]);

            if (empty($data)) {
                throw new \Exception('Token inválido');
            }

            if (count($data) > 1) {
                throw new \Exception('Token em conflito');
            }

            /* @var \Uloc\ApiBundle\Entity\ApiToken $token */
            $token = $data[0];
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        $user = $token->getUser();

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
        // Não chama devido a não ser o entry point
        return;
    }


    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }
}