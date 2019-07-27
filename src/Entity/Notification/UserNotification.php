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

use Uloc\ApiBundle\Entity\User\User;

/**
 * NotificationUser
 *
 */
class UserNotification
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
    private $dateShow;

    /**
     * @var \DateTime
     *
     */
    private $dateClick;

    /**
     * Muitos UserNotification tem Uma Notification.
     */
    private $notification;

    /**
     * Muitas UserNotification tem Um User.
     */
    private $user;

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * Set dateShow
     *
     * @param \DateTime $dateShow
     *
     * @return static
     */
    public function setDateShow($dateShow)
    {
        $this->dateShow = $dateShow;

        return $this;
    }

    /**
     * Get dateShow
     *
     * @return \DateTime
     */
    public function getDateShow()
    {
        return $this->dateShow;
    }

    /**
     * Set dateClick
     *
     * @param \DateTime $dateClick
     *
     * @return static
     */
    public function setDateClick($dateClick)
    {
        $this->dateClick = $dateClick;

        return $this;
    }

    /**
     * Get dateClick
     *
     * @return \DateTime
     */
    public function getDateClick()
    {
        return $this->dateClick;
    }
}
