<?php

namespace Uloc\ApiBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Uloc\ApiBundle\Manager\PersonManager;
use Uloc\ApiBundle\UlocApiBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class FunctionalTest extends AbstractFuncionalTest
{
    public function testServiceWiring()
    {
        $personManager = $this->container->get('uloc_api.manager.person_manager');
        $this->assertInstanceOf(PersonManager::class, $personManager);
    }
}