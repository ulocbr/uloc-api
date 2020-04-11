<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ApiProblemException;
use Uloc\ApiBundle\Exception\ApplicationErrorHandlerInterface;
use Uloc\ApiBundle\Helpers\Utils;
use Uloc\ApiBundle\Repository\User\UserRepository;
use Uloc\ApiBundle\Model\UserInterface;
use Uloc\ApiBundle\Serializer\ApiRepresentation;
use Uloc\ApiBundle\Services\ACL\AclPolicy;
use Uloc\ApiBundle\Services\JWT\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Uloc\ApiBundle\Services\Log\LogInterface;

abstract class BaseController extends AbstractController
{

    use Utils;

    const MAX_RESULT_LIMIT = 100;

    const NAO_ENCONTRADO = "Objeto não encontrado";
    const METODO_NAO_DISPONIVEL = "Médodo não disponível para esta rota";

    protected $logger;
    protected $errorHandler;

    public function __construct(LogInterface $logger, ApplicationErrorHandlerInterface $errorHandler)
    {
        $this->logger = $logger;
        $this->errorHandler = $errorHandler;
    }

    /**
     * O usuário atual está logado?
     *
     *
     * @return boolean
     */
    public function isUserLoggedIn(AuthorizationCheckerInterface $auth)
    {
        return $auth->isGranted('IS_AUTHENTICATED_FULLY');
    }

    /**
     * Responde não autorizado se o usuário não estiver logado
     */
    public function apiRequireLogin(AuthorizationCheckerInterface $auth)
    {
        if (!$auth->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->throwApiProblemException('Não autorizado. Você precisa estar autenticado para continuar esta ação.');
        }
    }

    /**
     * Loga um usuário no sistema
     *
     * @param UserInterface $user
     */
    public function loginUser(UserInterface $user, TokenStorageInterface $tokenStorage)
    {
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());

        $tokenStorage->setToken($token);
    }

    /**
     * @return UserRepository
     */
    protected function getUserRepository()
    {
        return $this->getDoctrine()
            ->getRepository(User::class);
    }

    /**
     * Retorna o Json Web Token
     * @param Request $request
     * @return string|null
     */
    protected function getToken(Request $request)
    {
        /*
        * TODO: Verificar tipo de autenticação para retornar o token correto JWT|Token
        */
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return;
        }

        return $token;
    }

    /*
     * Cria uma resposta HTTP para a API
     * @return Response
    */
    protected function createApiResponse($data, $statusCode = 200, $metadata = null, $group = 'public', $forcePhpEncode = null)
    {

        if (null !== $forcePhpEncode) {
            //TODO: implement others encoders (XML etc...)
            $data = \json_encode($data);
            $headers = array(
                'Content-Type' => 'application/uloc.console+json'
            );
            return new Response($data, $statusCode, $headers);
        }

        $json = $this->serialize($data, 'json', $group, $metadata);

        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/uloc.console+json'
        ));
    }

    /**
     * @param $data
     * @param int $code
     * @return Response
     */
    protected function createApiResponseEncodeArray($data, $code = 200)
    {
        return $this->createApiResponse($data, $code, null, null, true);
    }

    /*
    * Serializa um objeto ou array
    * @return string a JSON encoded string on success or FALSE on failure.
    */
    protected function serialize($data, $format = 'json', $group = 'public', $metadata = null)
    {
        $apiRepresentation = new ApiRepresentation();
        if (null !== $metadata) {
            $apiRepresentation->setMetadata($metadata);
        }
        $serialize = $apiRepresentation->serialize($data, $format, $group);

        return $serialize;
    }

    /**
     * Processa um formulário baseado na carga útil recebida
     * Process a form based on payload received
     * @param Request $request
     * @param FormInterface $form
     */
    protected function processForm(Request $request, FormInterface $form)
    {
        $data = \json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    /*
     * API Exception
     * @return ApiProblemException
    */
    protected function throwApiProblemException($message, $statusCode = 400)
    {

        $apiProblem = new ApiProblem(
            $statusCode,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $message);

        throw new ApiProblemException($apiProblem);
    }

    /*
     * Exception for an invalid handle submit
     * Exceção para um manipulador de formulário inválido
     * @return ApiProblemException
    */
    protected function throwApiProblemValidationException(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }

    public function getPagination(Request $request, $default, $max)
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $default);
        $limit = $limit > $max ? $max : $limit;
        $offset = ($page * $limit) - $limit;
        return [$page, $limit, $offset];
    }

    public static $IMAGE_TYPES = array('jpg', 'png', 'jpeg', 'gif');

    public function log()
    {
        // var_dump($this->logger);
    }

    public function checkAcl($acl)
    {
        $user = $this->getUser();
        $hasAdm = in_array('ROLE_ADMIN', $user->getRoles());
        if ($hasAdm) {
            return true;
        }
        return AclPolicy::checkAcl($acl, array_flip($user->getAcl()));
    }

    public function isGrantedAcl($acl)
    {
        if (!$this->checkAcl($acl)) {
            throw new AccessDeniedException(
                serialize([
                    'error' => 'authorization',
                    'errors' => 'Access Denied'
                ])
            );
        }
    }

}
