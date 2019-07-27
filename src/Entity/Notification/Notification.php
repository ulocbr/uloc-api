<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\Notification;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Notification
 *
 */
class Notification
{
    /**
     * @var int
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
     * @var \DateTime
     *
     */
    private $date;

    /**
     * @var \stdClass
     *
     */
    private $extra;

    /**
     * @var \DateTime
     *
     */
    private $showDate;

    /**
     * @var \DateTime
     *
     */
    private $expirationDate;

    /**
     * @var bool
     *
     */
    private $fixed;

    /**
     * @var int
     *
     */
    private $type;

    /**
     * @var int
     *
     */
    private $status;

    /**
     * Uma Notificacao tem Muitos UserNotification
     */
    private $users;

    /**
     * Notificacao constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param UserNotification $user
     */
    public function addUser(UserNotification $user)
    {
        $this->users[] = $user;
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
     * @return static
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
     * @return static
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return static
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
     * Set extra
     *
     * @param \stdClass $extra
     *
     * @return static
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

    /**
     * Set showDate
     *
     * @param \DateTime $showDate
     *
     * @return static
     */
    public function setShowDate($showDate)
    {
        $this->showDate = $showDate;

        return $this;
    }

    /**
     * Get showDate
     *
     * @return \DateTime
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return static
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set fixed
     *
     * @param boolean $fixed
     *
     * @return static
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return bool
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return static
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
     * Remove user
     *
     * @param UserNotification $user
     */
    public function removeUser(UserNotification $user)
    {
        $this->users->removeElement($user);
    }
}
