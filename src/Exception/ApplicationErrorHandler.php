<?php


namespace Uloc\ApiBundle\Exception;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Uloc\ApiBundle\Event\HandlerErrorEvent;
use Uloc\ApiBundle\Services\Log\LogInterface;

/**
 * Class ApplicationErrorHandler
 * Handle UlocApi common errors.
 * @package Uloc\ApiBundle\Exception
 */
class ApplicationErrorHandler implements ApplicationErrorHandlerInterface
{
    protected $logger;
    protected $eventDispatcher;
    protected $disableErrorEvent = false;

    public function __construct(LogInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return self
     */
    public function disableErrorEvent() {
        $this->disableErrorEvent = true;
        return $this;
    }

    public function handlerError($error, $responseFormat = 'json', $httpCode = 400)
    {
        /**
         * Permitir ao $responseFormat além de json, um callback para customizar o response.
         */

        try {
            !$this->disableErrorEvent && $this->eventDispatcher->dispatch(new HandlerErrorEvent($error), 'app.error');
        } catch (\Throwable $e) {}
        $this->disableErrorEvent = false;

        $unserialized = @unserialize($error);
        if ($unserialized === false) {
            if ($error === 'Not Found') {
                $error = ['error' => '404', 'message' => $error];
                $httpCode = 404;
            } else {
                $error = ['error' => 'exception', 'message' => $error];
            }
        } else {
            $error = $unserialized;
        }

        if (gettype($responseFormat) === 'object' && is_callable($responseFormat)) {
            return $responseFormat($error);
        }
        return new JsonResponse($error, $httpCode);
    }
}