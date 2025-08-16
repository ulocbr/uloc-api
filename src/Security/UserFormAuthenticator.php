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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Form\LoginForm;

/**
 * Login form authenticator compatible with Symfony 6/7.
 *
 * This class migrates the legacy Guard-based authenticator to the new
 * passport-based API. It uses the login form defined in LoginForm and
 * handles redirecting to the "home" route on success.
 */
class UserFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private FormFactoryInterface $formFactory;
    private EntityManagerInterface $em;
    private RouterInterface $router;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * Called on every request to decide if this authenticator should be used
     * for the request. Returning null means the authenticator will be used
     * when the login form is submitted.
     */
    public function supports(Request $request): ?bool
    {
        // Let the parent class decide when to trigger based on login URL and method
        // But ensure we only handle POST requests to the login route
        return $request->attributes->get('_route') === 'security_login_form'
            && $request->isMethod('POST');
    }

    /**
     * Build the Passport from the submitted login form data.
     */
    public function authenticate(Request $request): Passport
    {
        // Bind the form to the request to extract username & password
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $username = $data['_username'] ?? '';
        $password = $data['_password'] ?? '';

        // Store the last username in the session so the login form can re-populate it
        $request->getSession()->set(Security::LAST_USERNAME, $username);

        // Closure to load the User entity lazily when validating the passport
        $userLoader = function (string $userIdentifier): User {
            return $this->em->getRepository(User::class)->loadUserByUsername($userIdentifier);
        };

        return new Passport(
            new UserBadge($username, $userLoader),
            new PasswordCredentials($password)
        );
    }

    /**
     * Called when authentication executed successfully. Redirect the user to
     * the home page. Returning null would continue the request normally.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    /**
     * Called when authentication fails. Store the error in the session and
     * redirect back to the login page.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $session = $request->getSession();
        if ($session) {
            $session->set(Security::AUTHENTICATION_ERROR, $exception);
        }
        return new RedirectResponse($this->getLoginUrl($request));
    }

    /**
     * Return the login route URL. AbstractLoginFormAuthenticator will call
     * this when a request requires authentication but no credentials were
     * provided.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('security_login_form');
    }
}
