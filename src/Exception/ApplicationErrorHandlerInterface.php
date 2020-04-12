<?php


namespace Uloc\ApiBundle\Exception;


use Uloc\ApiBundle\Services\Log\LogInterface;

interface ApplicationErrorHandlerInterface
{
    /**
     * ApplicationErrorHandlerInterface constructor.
     * @param LogInterface $logger
     */
    public function __construct(LogInterface $logger);

    /**
     * @param mixed $error
     * @param string $responseFormat
     * @return mixed
     */
    public function handlerError($error, $responseFormat = 'json');
}