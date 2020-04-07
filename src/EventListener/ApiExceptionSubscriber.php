<?php

namespace Uloc\ApiBundle\EventListener;

use Uloc\ApiBundle\Api\ApiProblem;
use Uloc\ApiBundle\Api\ApiProblemException;
use Uloc\ApiBundle\Api\ResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Uloc\ApiBundle\Services\Log\SystemLog;

/*
 * Ouvinte para uma exceção que envolva a API
 */

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $debug;

    private $responseFactory;

    private $logger;

    public function __construct($debug, ResponseFactory $responseFactory, SystemLog $logger)
    {
        $this->debug = $debug;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event) // TODO: Change from GetResponseForExceptionEvent
    {
        // only reply to /api and /uapi URLs
        if (strpos($event->getRequest()->getPathInfo(), '/api') !== 0 && strpos($event->getRequest()->getPathInfo(), '/uapi') !== 0) {
            return;
        }

        $e = $event->getThrowable();

        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        // allow 500 errors to be thrown
        if ($this->debug && $statusCode >= 500) {
            return;
        }

        $this->logException($e);

        if ($e instanceof ApiProblemException) {
            $apiProblem = $e->getApiProblem();
        } else {


            $apiProblem = new ApiProblem(
                $statusCode
            );

            /*
             * If it's an HttpException message (e.g. for 404, 403),
             * we'll say as a rule that the exception message is safe
             * for the client. Otherwise, it could be some sensitive
             * low-level exception, which should *not* be exposed
             */
            if ($e instanceof HttpExceptionInterface) {
                $apiProblem->set('detail', $e->getMessage());
            }
        }

        $response = $this->responseFactory->createResponse($apiProblem);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }

    /**
     * Adapted from the core Symfony exception handling in ExceptionListener
     *
     * @param \Exception $exception
     */
    private function logException(\Exception $exception)
    {
        $message = sprintf('Uncaught PHP Exception %s: "%s" at %s line %s', get_class($exception), $exception->getMessage(), $exception->getFile(), $exception->getLine());
        $isCritical = !$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500;
        $context = array('exception' => $exception);
        if ($isCritical) {
            $this->logger->critical($message, $context);
        } else {
            $this->logger->error($message, $context);
        }
    }
}
