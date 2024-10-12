<?php

namespace Uloc\ApiBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class HandlerErrorEvent
 * Notifies errors
 * @package Uloc\Bundle\AppBundle\Event
 */
class HandlerErrorEvent extends Event
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }


}