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


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Uloc\ApiBundle\Api\ResponseFactory;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $responseFactory;

    public function __construct(EntityManager $em, ResponseFactory $responseFactory)
    {
        $this->em = $em;
        $this->responseFactory = $responseFactory;
    }

    public function getCredentials(Request $request)
    {
        /**
         * Extrai o token
         */
        $token = $request->get('token');
        return;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // TODO: Implement getUser() method.
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        // TODO: Implement createAuthenticatedToken() method.
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // Não chama devido a não ser o entry point
        return;
    }


    public function supports(Request $request)
    {
        // TODO: Implement supports() method.
    }
}