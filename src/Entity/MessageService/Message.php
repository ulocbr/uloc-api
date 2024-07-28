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

    private $ccs;
    private $bccs;
    private $replyTo;
    private $priority;

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
    private $subject;

    /**
     * @var string
     *
     */
    private $message;

    /**
     * @var string
     *
     */
    private $messageText;

    /**
     * @var \DateTime
     *
     */
    private $scheduleFor;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var array
     */
    private $extra = [];

    /**
     * @var string
     */
    private $session = 'default';

    /**
     * @var integer
     *
     */
    private $attempts = 0;

    /**
     * @var string
     */
    private $referEntity;

    /**
     * @var integer
     */
    private $referEntityId;

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

    public function clearLogs() {
        $this->logs = new ArrayCollection();
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

    public function clearAttachments() {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
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
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
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

    /**
     * @return \DateTime
     */
    public function getScheduleFor()
    {
        return $this->scheduleFor;
    }

    /**
     * @param \DateTime $scheduleFor
     */
    public function setScheduleFor(?\DateTime $scheduleFor)
    {
        $this->scheduleFor = $scheduleFor;
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

    /**
     * @return array
     */
    public function getConfig(): ?array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(?array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     */
    public function setAttempts(int $attempts): void
    {
        $this->attempts = $attempts;
    }

    /**
     */
    public function addAttempt()
    {
        $this->attempts++;
    }

    /**
     * @return mixed
     */
    public function getCcs()
    {
        return $this->ccs;
    }

    /**
     * @param mixed $ccs
     */
    public function setCcs($ccs): void
    {
        $this->ccs = $ccs;
    }

    /**
     * @return mixed
     */
    public function getBccs()
    {
        return $this->bccs;
    }

    /**
     * @param mixed $bccs
     */
    public function setBccs($bccs): void
    {
        $this->bccs = $bccs;
    }

    /**
     * @return mixed
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param mixed $replyTo
     */
    public function setReplyTo($replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return array
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     */
    public function setExtra(?array $extra): void
    {
        $this->extra = $extra;
    }

    /**
     * @return string
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession($session): void
    {
        $this->session = $session;
    }

    public function getReferEntity(): ?string
    {
        return $this->referEntity;
    }

    public function setReferEntity(?string $referEntity): void
    {
        $this->referEntity = $referEntity;
    }

    public function getReferEntityId(): ?int
    {
        return $this->referEntityId;
    }

    public function setReferEntityId(?int $referEntityId): void
    {
        $this->referEntityId = $referEntityId;
    }

}
