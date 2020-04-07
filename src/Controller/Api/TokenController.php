<?php

namespace Uloc\ApiBundle\Controller\Api;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Uloc\ApiBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Api\Exception\BadCredentialsException;

class TokenController extends BaseController
{
    public function newToken(Request $request, JWTEncoderInterface $encoder, UserPasswordEncoderInterface $passwordEncoder)
    {
        $userGET = $request->request->get('user');
        if (strlen($userGET) < 2) {
            throw new BadCredentialsException('Usuário inválido');
        }
        $passGET = $request->request->get('pass');
        if (strlen($passGET) < 3) {
            throw new BadCredentialsException('Senha inválida');
        }

        // TODO: Move to UserManager
        /** @var User $user */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadUserByUsername($userGET, false);

        if (!$user) {
            throw $this->createNotFoundException("Credenciais não encontrada");
        }

        $roles = $user->getRoles();
        if (!is_array($roles)) {
            return new JsonResponse(['error' => 'Usuário sem permissão'], JsonResponse::HTTP_NOT_FOUND);
        }

        $channel = $request->get('channel');

        if (!in_array('ROLE_API', $roles) && !in_array('ROLE_ROOT', $roles) && $channel !== 'client') {
            throw new BadCredentialsException('Usuário sem permissão de acesso à api');
        }

        $isValid = $passwordEncoder
            ->isPasswordValid($user, $passGET);

        if (!$isValid) {
            throw new BadCredentialsException('Credenciais inválidas');
        }

        $token = $encoder->encode([
                'username' => $user->getUsername(),
                'exp' => time() + (3600 * 24) // 1 day expiration
            ]);

        $userContent = [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "name" => $user->getPerson() ? $user->getPerson()->getName() : $user->getUsername(),
            "foto" => 'https://www.gravatar.com/avatar/' . trim(strtolower(md5($user->getEmail()))),

        ];

        if ($user->getPerson()) {
            $userContent['person'] = $user->getPerson()->getId();
        }


        return new JsonResponse([
            'token' => $token,
            'user' => $userContent
        ]);
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