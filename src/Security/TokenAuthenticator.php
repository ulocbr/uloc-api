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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ResponseFactory;
use Uloc\ApiBundle\Entity\ApiToken;
use Uloc\ApiBundle\Entity\User\User;

class TokenAuthenticator extends AbstractAuthenticator
{
    private EntityManagerInterface $em;
    private ResponseFactory $responseFactory;

    private const AUTH_HEADER = 'X-API-KEY';

    public function __construct(EntityManagerInterface $em, ResponseFactory $responseFactory)
    {
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::AUTH_HEADER);
    }

    public function authenticate(Request $request): Passport
    {
        $tokenString = $request->headers->get(self::AUTH_HEADER);
        if (!$tokenString) {
            throw new CustomUserMessageAuthenticationException('Missing credentials');
        }

        $data = $this->em->getRepository(ApiToken::class)->findBy([
            'token' => $tokenString,
        ]);

        if (empty($data)) {
            throw new CustomUserMessageAuthenticationException('Token inválido');
        }

        if (count($data) > 1) {
            throw new CustomUserMessageAuthenticationException('Token em conflito');
        }

        /** @var ApiToken $apiToken */
        $apiToken = $data[0];
        $user = $apiToken->getUser();

        if (!method_exists($user, 'getRoles') || count($user->getRoles()) < 1) {
            throw new CustomUserMessageAuthenticationException('Invalid Roles');
        }

        $identifier = method_exists($user, 'getUserIdentifier') ? $user->getUserIdentifier() : $user->getUsername();
        $userLoader = function (string $userIdentifier) use ($user): User {
            return $user;
        };

        return new SelfValidatingPassport(new UserBadge($identifier, $userLoader));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // do nothing; let the controller handle the response
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
        $apiProblem = new ApiProblem(401);
        $message = $authException ? $authException->getMessageKey() : 'Missing credentials';
        $apiProblem->set('detail', $message);

        return $this->responseFactory->createResponse($apiProblem);
    }
}