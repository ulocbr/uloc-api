<?php

namespace Uloc\ApiBundle\Controller\Api;

use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ApiProblemException;
use Uloc\ApiBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Form\UserApiType;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadata;

/**
 * User controller.
 *
 */
class UserController extends BaseController
{

    /**
     * Lists all entities.
     *
     * @Route("/api/users/", name="api_users_index")
     * @Route("/api/users")
     * @Method("GET")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        list($page, $limit, $offset) = $this->getPagination($request, 2, self::MAX_RESULT_LIMIT);

        $filtros = [];

        $busca = $request->query->get('busca');
        if (strlen(trim($busca)) > 0) {
            $filtros['busca'] = $busca;
        }

        $tipo = $request->query->get('tipo');
        if (strlen(trim($tipo)) > 0) {
            $filtros['tipo'] = $tipo;
        }

        $data = $em->getRepository('UlocAppBundle:User')->findAllSimple($limit, $offset, $filtros);
        $total = $data['total'];

        $response = array(
            'result' => $this->serialize($data['result'], 'array', 'public', function (ApiRepresentationMetadata $metadata) {
                User::loadApiRepresentation($metadata);
            }),
            "limit" => $limit,
            "total" => (int)$total,
            "totalPages" => ceil($total / $limit),
            "offset" => $page
        );

        return $this->createApiResponseEncodeArray($response);
    }

    /**
     * @Route("/api/users/{id}", name="api_user_show")
     * @Method("GET")
     */
    public function showAction(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->createApiResponse($user, 200, null, 'api_edit');
    }

    /**
     * @Route("/api/users/{id}", name="api_usurio_edit")
     * @Method({"PATCH", "PUT"})
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserApiType::class, $user);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->showAction($user, $request);
    }

    /**
     * @Route("/api/users/", name="api_usurio_new")
     * @Route("/api/users")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserApiType::class, $user);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $data = json_decode($request->getContent(), true);
        $plainPassword = @$data['password'];
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $roles = ['ROLE_USER', 'ROLE_INTRANET'];
        if($request->get('role') === 'comitente'){
            $roles[] = 'ROLE_COMITENTE';
        }
        $user->setRoles($roles);

        $em = $this->getDoctrine()->getManager();

        $personID = intval(@$data['person']['id']);
        if ($personID > 0) {
            $person = $em->getRepository(Person::class)->find($personID);
            if(!$person){
                return $this->throwApiProblemException('Person não encontrada');
            }
            $user->setPerson($person);
            $person->addUser($user);
        }

        $em->persist($user);
        $em->flush();

        return $this->showAction($user, $request);
    }

    /**
     * @Route("/api/users/{id}", name="api_usurio_delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        /*if ($user->getPerson()) {
            $em->remove($user->getPerson());
        }*/
        $em->flush();

        return $this->createApiResponseEncodeArray([], 200);
    }

    /**
     * @Route("/api/users/{id}/password", name="api_usurio_password_update")
     * @Method({"PATCH", "PUT"})
     */
    public function updatePasswordAction(Request $request, User $user)
    {

        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $plainPassword = @$data['password'];
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->createApiResponseEncodeArray(['status' => 'updated'], 200);
    }
}
