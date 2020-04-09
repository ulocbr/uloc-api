<?php

namespace Uloc\ApiBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class UserNewTokenEvent
 * Notifies listeners when a token is created for a user, allowing changes to response data
 * @package Uloc\Bundle\AppBundle\Event
 */
class UserNewTokenEvent extends Event
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


}