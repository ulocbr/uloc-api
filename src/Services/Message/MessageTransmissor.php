<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Message;


use Uloc\ApiBundle\Services\Config\ConfigServiceInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

class MessageTransmissor implements MessageServiceTransmissorInterface
{

    /* @var $message \Uloc\ApiBundle\Services\Message\MessageServiceInterface */
    protected $message;

    protected $configService;
    protected $logger;

    protected $transmissors;

    /**
     * MessageTransmissor constructor.
     * @param ConfigServiceInterface $configService
     */
    public function __construct(ConfigServiceInterface $configService, LogInterface $logger)
    {
        $this->configService = $configService;
        $this->logger = $logger;
        $this->transmissors = [];
    }

    public function setMessage(MessageServiceInterface $message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function addTransmissor($transmissor, $alias)
    {
        $this->transmissors[$alias] = $transmissor;
    }

    public function getTransmissor($alias)
    {
        if (array_key_exists($alias, $this->transmissors)) {
            return $this->transmissors[$alias];
        }
    }

    public function getTransmissors()
    {
        return $this->transmissors;
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function transmit()
    {
        // TODO: Implement transmit() method.
    }
}