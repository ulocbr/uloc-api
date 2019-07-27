<?php

namespace Uloc\ApiBundle\Entity\MessageService;

use Doctrine\ORM\Mapping as ORM;
use Uloc\ApiBundle\Entity\App\File;

/**
 * Há recomendações que os objetos menores que 256K são melhor armazenados em um
 * banco de dados, enquanto os objetos maiores que 1M são melhor armazenados no
 * sistema de arquivos.
 *
 * Como não teremos este controle, decidi por manter os objetos no sistema de
 * arquivos do SO (filesystem).
 *
 */
class MessageAttachment extends File
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
    private $name;

    /**
     * @var string
     *
     */
    private $description;

    /**
     * @var int
     *
     */
    private $size;

    /**
     * Muitos Attachments tem Um Message.
     */
    private $message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
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
     * Set name
     *
     * @param string $name
     *
     * @return MessageAttachment
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
     * Set description
     *
     * @param string $description
     *
     * @return MessageAttachment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return MessageAttachment
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
