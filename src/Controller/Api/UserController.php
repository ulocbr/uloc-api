<?php

namespace Uloc\ApiBundle\Controller\Api;

use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ApiProblemException;
use Uloc\ApiBundle\Controller\BaseController;
// Use Symfony's built‑in Route attribute instead of the deprecated Sensio annotations.  
// The Method annotation has been removed in Symfony 6+, so we specify HTTP methods
// via the `methods` option on the Route attribute.  
use Symfony\Component\Routing\Annotation\Route;
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

        $data = $em->getRepository('UlocAppBundle:User')->findAllUserSimple($limit, $offset, $filtros);
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

    #[Route('/api/users/{id}', name: 'api_user_show', methods: ['GET'])]
    public function showAction(User $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->createApiResponse($user, 200, null, 'api_edit');
    }

    #[Route('/api/users/{id}', name: 'api_usurio_edit', methods: ['PATCH','PUT'])]
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

    #[Route('/api/users/', name: 'api_usurio_new', methods: ['POST'])]
    #[Route('/api/users', methods: ['POST'])]
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
        // Use the new PasswordHasher instead of the deprecated password encoder.  
        // See Symfony docs for details: `UserPasswordHasherInterface::hashPassword()`【648800746586457†L401-L406】.  
        $passwordHasher = $this->get('security.user_password_hasher');
        $password = $passwordHasher->hashPassword($user, (string) $plainPassword);
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

    #[Route('/api/users/{id}', name: 'api_usurio_delete', methods: ['DELETE'])]
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

    #[Route('/api/users/{id}/password', name: 'api_usurio_password_update', methods: ['PATCH','PUT'])]
    public function updatePasswordAction(Request $request, User $user)
    {

        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiProblem(400, ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT);

            throw new ApiProblemException($apiProblem);
        }

        $plainPassword = @$data['password'];
        // Hash the new password using the PasswordHasher interface【648800746586457†L401-L406】.
        $passwordHasher = $this->get('security.user_password_hasher');
        $password = $passwordHasher->hashPassword($user, (string) $plainPassword);
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->createApiResponseEncodeArray(['status' => 'updated'], 200);
    }
}
