<?php

namespace Uloc\ApiBundle\Entity\MessageService;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Canal de comunicação do serviço de message.
 *
 * Exemplo:
 *
 * Name: Email
 * Classname: EmailTransmissor
 *
 */
class MessageType
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $classname;

    /**
     * @var \stdClass
     *
     */
    private $config;

    /**
     * @var string
     *
     */
    private $name;

    /**
     * @var ArrayCollection|null
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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
     * Set classname
     *
     * @param string $classname
     *
     * @return MessageType
     */
    public function setClassname($classname)
    {
        $this->classname = $classname;

        return $this;
    }

    /**
     * Get classname
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * Set config
     *
     * @param \stdClass $config
     *
     * @return MessageType
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get config
     *
     * @return \stdClass
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return MessageType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection|Message
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param Message[] $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }
}
