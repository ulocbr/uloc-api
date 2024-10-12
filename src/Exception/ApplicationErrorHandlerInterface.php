<?php


namespace Uloc\ApiBundle\Exception;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

interface ApplicationErrorHandlerInterface
{
    /**
     * ApplicationErrorHandlerInterface constructor.
     * @param LogInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(LogInterface $logger, EventDispatcherInterface $eventDispatcher);

    /**
     * @return self
     */
    public function disableErrorEvent();
    /**
     * @param mixed $error
     * @param string $responseFormat
     * @return mixed
     */
    public function handlerError($error, $responseFormat = 'json');
}