<?php

namespace Uloc\ApiBundle\Entity\MessageService;

use Doctrine\ORM\Mapping as ORM;

/**
 * Armazena um histórico à message sempre que necessário.
 *
 */
class MessageLog
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
    private $date;

    /**
     * @var string
     *
     */
    private $subject;

    /**
     * @var string
     *
     */
    private $description;

    /**
     * @var \stdClass
     *
     */
    private $extra;

    /**
     * Muitos Logs tem Um Message.
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MessageLog
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return MessageLog
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return MessageLog
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
     * Set extra
     *
     * @param \stdClass $extra
     *
     * @return MessageLog
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return \stdClass
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
