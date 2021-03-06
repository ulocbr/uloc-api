<?php


namespace Uloc\ApiBundle\Exception;


use Symfony\Component\HttpFoundation\JsonResponse;
use Uloc\ApiBundle\Services\Log\LogInterface;

/**
 * Class ApplicationErrorHandler
 * Handle UlocApi common errors.
 * @package Uloc\ApiBundle\Exception
 */
class ApplicationErrorHandler implements ApplicationErrorHandlerInterface
{
    protected $logger;

    public function __construct(LogInterface $logger)
    {
        $this->logger = $logger;
    }


    public function handlerError($error, $responseFormat = 'json', $httpCode = 400)
    {
        /**
         * TODO: Trata erros e armazena-os caso necessário.
         * Permitir ao $responseFormat além de json, um callback para customizar o response.
         */

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