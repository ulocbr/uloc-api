<?php

namespace Uloc\ApiBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Uloc\ApiBundle\Entity\AuthSecurity;

class User2FAEvent extends Event
{
    private $authSecurity;

    public function __construct(AuthSecurity $authSecurity)
    {
        $this->authSecurity = $authSecurity;
    }

    /**
     * @return AuthSecurity
     */
    public function getData(): AuthSecurity
    {
        return $this->authSecurity;
    }

    /**
     * @param AuthSecurity $data
     */
    public function setData(AuthSecurity $authSecurity): void
    {
        $this->authSecurity = $authSecurity;
    }


}