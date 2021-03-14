<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\App;

use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * @author Tiago Felipe
 * @version 0.0.1
 *
 */
class Log
{

    protected $id;

    protected $type;

    protected $entity;
    protected $entityId;

    protected $user;
    protected $userId;

    protected $action;

    protected $message;

    protected $context;

    protected $oldObject;
    protected $newObject;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param mixed $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getOldObject()
    {
        return $this->oldObject;
    }

    /**
     * @param mixed $oldObject
     */
    public function setOldObject($oldObject)
    {
        $this->oldObject = $oldObject;
    }

    /**
     * @return mixed
     */
    public function getNewObject()
    {
        return $this->newObject;
    }

    /**
     * @param mixed $newObject
     */
    public function setNewObject($newObject)
    {
        $this->newObject = $newObject;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        $public = [
            'id',
            'type',
            'entity',
            'entityId',
            'user',
            'userId',
            'action',
            'message',
            'context',
            'oldObject',
            'newObject',
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }

}
