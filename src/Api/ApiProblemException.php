<?php

namespace Uloc\ApiBundle\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Uloc\ApiBundle\Api\ApiProblem as ApiProblem;


class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct(ApiProblem $apiProblem, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getApiProblem()
    {
        return $this->apiProblem;
    }
}
