<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'uloc_api.logger' shared service.

return $this->services['uloc_api.logger'] = new \Uloc\ApiBundle\Services\Log\SystemLog(new \Symfony\Component\HttpKernel\Log\Logger());
