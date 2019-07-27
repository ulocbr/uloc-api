<?php

namespace Uloc\ApiBundle\Entity\MessageService;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Services\Message\MessageServiceInterface;

/**
 * Message
 *
 */
class Message implements MessageServiceInterface
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var \DateTime
     *
     */
    private $dateRegistry;

    /**
     * @var \DateTime
     *
     */
    private $dateSend;

    /**
     * @var \DateTime
     *
     */
    private $dateReceiver;

    /**
     * @var \DateTime
     *
     */
    private $dateReaded;

    /**
     * @var string
     *
     */
    private $senderName;

    /**
     * @var string
     *
     */
    private $sender;

    /**
     * @var string
     *
     */
    private $recipientName;

    /**
     * @var string
     *
     */
    private $recipient;

    /**
     * 0=Criado
     * 1=Enviado
     * 2=Recebido
     * 3=Lido
     * 9=Erro
     *
     * @var int
     *
     */
    private $status;

    /**
     * @var string
     *
     */
    private $message;

    /**
     * Muitos Message tem Um MessageType.
     */
    private $type;

    /**
     * Um Message tem Muitos Attachments
     */
    private $attachments;

    /**
     * Um Message tem Muitos Logs
     */
    private $logs;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param MessageLog $log
     */
    public function addLog(MessageLog $log)
    {
        $this->logs[] = $log;
    }

    /**
     * @return mixed
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param MessageAttachment $attachment
     */
    public function addAttachment(MessageAttachment $attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MessageType $type
     */
    public function setType(MessageType $type)
    {
        $this->type = $type;
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
     * Set dateRegistry
     *
     * @param \DateTime $dateRegistry
     *
     * @return Message
     */
    public function setDateRegistry($dateRegistry)
    {
        $this->dateRegistry = $dateRegistry;

        return $this;
    }

    /**
     * Get dateRegistry
     *
     * @return \DateTime
     */
    public function getDateRegistry()
    {
        return $this->dateRegistry;
    }

    /**
     * Set dateSend
     *
     * @param \DateTime $dateSend
     *
     * @return Message
     */
    public function setDateSend($dateSend)
    {
        $this->dateSend = $dateSend;

        return $this;
    }

    /**
     * Get dateSend
     *
     * @return \DateTime
     */
    public function getDateSend()
    {
        return $this->dateSend;
    }

    /**
     * Set dateReceiver
     *
     * @param \DateTime $dateReceiver
     *
     * @return Message
     */
    public function setDateReceiver($dateReceiver)
    {
        $this->dateReceiver = $dateReceiver;

        return $this;
    }

    /**
     * Get dateReceiver
     *
     * @return \DateTime
     */
    public function getDateReceiver()
    {
        return $this->dateReceiver;
    }

    /**
     * Set dateReaded
     *
     * @param \DateTime $dateReaded
     *
     * @return Message
     */
    public function setDateReaded($dateReaded)
    {
        $this->dateReaded = $dateReaded;

        return $this;
    }

    /**
     * Get dateReaded
     *
     * @return \DateTime
     */
    public function getDateReaded()
    {
        return $this->dateReaded;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     *
     * @return Message
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * Get senderName
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Set sender
     *
     * @param string $sender
     *
     * @return Message
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set recipientName
     *
     * @param string $recipientName
     *
     * @return Message
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    /**
     * Get recipientName
     *
     * @return string
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * Set recipient
     *
     * @param string $recipient
     *
     * @return Message
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Message
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function setTransmissor()
    {
        // TODO: Implement setTransmissor() method.
    }

    public function getTransmissor()
    {
        // TODO: Implement getTransmissor() method.
    }


    /**
     * Remove attachment
     *
     * @param \Uloc\ApiBundle\Entity\MessageService\MessageAttachment $attachment
     */
    public function removeAttachment(\Uloc\ApiBundle\Entity\MessageService\MessageAttachment $attachment)
    {
        $this->attachments->removeElement($attachment);
    }

    /**
     * Remove log
     *
     * @param \Uloc\ApiBundle\Entity\MessageService\MessageLog $log
     */
    public function removeLog(\Uloc\ApiBundle\Entity\MessageService\MessageLog $log)
    {
        $this->logs->removeElement($log);
    }
}
