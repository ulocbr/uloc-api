<?php

namespace Uloc\ApiBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Uloc\ApiBundle\Manager\UserManagerInterface;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Uloc\ApiBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Api\Exception\BadCredentialsException;

class TokenController extends BaseController
{
    /**
     * TODO: DOC! Doc use this or rewrite
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function newToken(Request $request, UserManagerInterface $userManager)
    {
        $userGET = $request->request->get('user');
        if (strlen($userGET) < 2) {
            throw new BadCredentialsException('Usuário inválido');
        }
        $passGET = $request->request->get('pass');
        if (strlen($passGET) < 3) {
            throw new BadCredentialsException('Senha inválida');
        }

        /** @var User $user */
        $user = $userManager->findByUsername($userGET);

        if (!$user) {
            throw $this->createNotFoundException("Credenciais não encontrada");
        }

        $userManager->manager($user);
        $roles = $user->getRoles();
        if (!is_array($roles)) {
            return new JsonResponse(['error' => 'Usuário sem permissão'], JsonResponse::HTTP_NOT_FOUND);
        }

        $channel = $request->get('channel');

        if (!in_array('ROLE_API', $roles) && !in_array('ROLE_ROOT', $roles) && $channel !== 'client') {
            throw new BadCredentialsException('Usuário sem permissão de acesso à api');
        }

        $isValid = $userManager->isPasswordValid($passGET);

        if (!$isValid) {
            throw new BadCredentialsException('Credenciais inválidas');
        }

        $token = $userManager->generateToken();

        $userContent = $userManager->getUserContent();

        if ($user->getPerson()) {
            $userContent['person'] = $user->getPerson()->getId();
        }

        $user->setLastLogin(new \DateTime());
        $userManager->update();

        $response = new JsonResponse([
            'token' => $token,
            'user' => $userContent
        ]);
        $cookie = Cookie::create('sl_session')
            ->withValue($token)
            ->withExpires(time() + 86400)
            ->withSecure(true)
            ->withSameSite('None');
        // ->withDomain('.suporteleiloes.com')
        // ->withSecure(true);
        $response->headers->setCookie($cookie);
        $refer = $request->headers->get('referer');
        if (!empty($refer)) {
            $response->headers->set('Access-Control-Allow-Origin', filter_var($refer, FILTER_SANITIZE_URL));
        }
        $response->headers->set('Access-Control-Allow-Credentials', true);

        return $response;
    }

    public function userCredentials(Request $request)
    {
        $user = $this->getUser();

        $response = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "token" => $user->getPassword(),
        ];

        if ($user->getPerson()) {
            $response = array_merge($response, ["person" => [
                "id" => $user->getPerson()->getId(),
                "name" => $user->getPerson()->getName()
            ]]);
        }

        return $this->createApiResponseEncodeArray($response);
    }
}