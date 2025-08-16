<?php

namespace Uloc\ApiBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ResponseFactory;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Uloc\ApiBundle\Services\JWT\TokenExtractor\AuthorizationHeaderTokenExtractor;

class JwtTokenAuthenticator extends AbstractAuthenticator
{
    private JWTEncoderInterface $jwtEncoder;
    private EntityManagerInterface $em;
    private ResponseFactory $responseFactory;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        EntityManagerInterface $em,
        ResponseFactory $responseFactory
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $extractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $extractor->extract($request);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Missing credentials');
        }

        try {
            $data = $this->jwtEncoder->decode($token);
        } catch (\Throwable $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }

        if (!isset($data['username'])) {
            throw new CustomUserMessageAuthenticationException('Invalid token payload');
        }

        $username = $data['username'];

        // Validação opcional do "client" conforme sua regra atual
        $expectedClient = $_SERVER['USER_CLIENT'] ?? null;
        $tokenClient = $data['client'] ?? null;

        $userLoader = function (string $userIdentifier): UserInterface {
            $user = $this->em->getRepository(User::class)
                ->findOneBy(['username' => $userIdentifier, 'deleted' => false]);

            if (!$user instanceof User) {
                throw new CustomUserMessageAuthenticationException('Invalid User');
            }

            if (!method_exists($user, 'getRoles') || count($user->getRoles()) < 1) {
                throw new CustomUserMessageAuthenticationException('Invalid Roles');
            }

            return $user;
        };

        // Se precisar travar por client, valide aqui antes de carregar o usuário
        if ($expectedClient !== null && $tokenClient !== $expectedClient) {
            throw new CustomUserMessageAuthenticationException(sprintf(
                'Invalid User Client Session %s/%s',
                (string) $tokenClient,
                (string) $expectedClient
            ));
        }

        return new SelfValidatingPassport(new UserBadge($username, $userLoader));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Sem redirecionar; deixa o controller seguir
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $apiProblem = new ApiProblem(401);
        $apiProblem->set('detail', $exception->getMessageKey());

        return $this->responseFactory->createResponse($apiProblem);
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        // chamado quando a requisição requer auth e não há credenciais
        $apiProblem = new ApiProblem(401);
        $message = $authException ? $authException->getMessageKey() : 'Missing credentials';
        $apiProblem->set('detail', $message);

        return $this->responseFactory->createResponse($apiProblem);
    }
}
