<?php

namespace Uloc\ApiBundle\Entity\Person;

use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Services\Message\MessageServiceInterface;

//TODO: Deve existir um event listener para escutar o ServicoMensagem e registrar um histórico de comunicação com uma Person

/**
 * CommunicationHistory
 *
 */
class CommunicationHistory
{

    /**
     * Método facilitador para construir um histórico de comunicação baseado no Serviço de Mensagem da aplicação
     * @param Person $person
     * @param MessageServiceInterface $message
     * @param User $user
     * @return CommunicationHistory instance
     */
    public static function factory(Person $person, MessageServiceInterface $message, User $user = null){
        $class = new CommunicationHistory();
        $class->setPerson($person);
        if($user !== null){
            $class->setUser($user);
        }
        // $class->setMessage($message->getMessage());
        $class->setMessageId($message->getId()); //TODO: Verificar melhor forma de implementar isto
        return $class;
    }

    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var integer
     *
     */
    private $status;

    /**
     * @var integer
     *
     */
    private $messageId;

    /**
     * Muitos CommunicationHistory tem Um Person.
     */
    private $person;

    /**
     * Muitos Históricos podem estarem relacionados à somente um usuário do sistema
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person = null)
    {
        $this->person = $person;
    }

    /**
     * Set user
     *
     * @param \Uloc\ApiBundle\Entity\User\User $user
     *
     * @return CommunicationHistory
     */
    public function setUser(\Uloc\ApiBundle\Entity\User\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Uloc\ApiBundle\Entity\User\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }
}
