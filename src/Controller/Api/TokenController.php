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

    public static $AuthHeaders = [];
    public static $AuthCookies = [];
    public static $AuthExtraResponse = [];
    public static $AuthResponseData = [];

    /**
     * TODO: DOC! Doc use this or rewrite
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse
     * @throws \Exception
     */
    public function newToken(Request $request, UserManagerInterface $userManager)
    {
        try {
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
                // return new JsonResponse(['error' => 'Usuário sem permissão'], JsonResponse::HTTP_NOT_FOUND);
                throw new \Exception('Usuário sem permissão');
            }

            $channel = $request->get('channel');

            if (!in_array('ROLE_API', $roles) && !in_array('ROLE_ROOT', $roles) && $channel !== 'client') {
                throw new \Exception('Usuário sem permissão de acesso à api');
            }

            $isValid = $userManager->isPasswordValid($passGET);

            if (!$isValid) {
                throw new \Exception('Credenciais inválidas');
            }

            $token = $userManager->generateToken();

            $userContent = $userManager->getUserContent();

            if ($user->getPerson()) {
                $userContent['person'] = $user->getPerson()->getId();
            }

            $user->setLastLogin(new \DateTime());
            $userManager->update();

            $data = [
                'token' => $token,
                'user' => $userContent
            ];

            if (count(self::$AuthExtraResponse)) {
                $data['extra'] = self::$AuthExtraResponse;
            }

            $response = new JsonResponse($data);
            /*$cookie = Cookie::create('sl_session')
                ->withValue($token)
                ->withExpires(time() + 86400)
                ->withSecure(true)
                ->withSameSite('None');*/
            // ->withDomain('.suporteleiloes.com')
            // ->withSecure(true);
            if (count(self::$AuthCookies)) {
                foreach (self::$AuthCookies as $cookie) {
                    $response->headers->setCookie($cookie($data));
                }
            }

            if (count(self::$AuthHeaders)) {
                foreach (self::$AuthHeaders as $header) {
                    $response->headers->set($header['key'], is_callable($header['value']) ? $header['value']($data) : $header['value']);
                }
            }

            #$response->headers->setCookie($cookie);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $refer = $request->headers->get('origin');
            if (!empty($refer)) {
                $response->headers->set('Access-Control-Allow-Origin', filter_var($refer, FILTER_SANITIZE_URL));
            }

            self::$AuthResponseData = $data;
            self::$AuthResponseData['user'] = $user;
            return $response;
        } catch (\Exception $e) {
            self::$AuthResponseData = [
                'detail' => $e->getMessage(),
                'status' => 401,
                'title' => 'Unauthorized',
                'type' => 'authentication'
            ];
            $response = new JsonResponse(self::$AuthResponseData, 401);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $refer = $request->headers->get('origin');
            if (!empty($refer)) {
                $response->headers->set('Access-Control-Allow-Origin', filter_var($refer, FILTER_SANITIZE_URL));
            }

            return $response;
        }

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