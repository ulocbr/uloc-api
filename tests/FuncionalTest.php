<?php

namespace Uloc\ApiBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Uloc\ApiBundle\Manager\PersonManager;
use Uloc\ApiBundle\UlocApiBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class FunctionalTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new UlocApiTestingKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();

        $personManager = $container->get('uloc_api.manager.person_manager');
        $this->assertInstanceOf(PersonManager::class, $personManager);
    }
}

class UlocApiTestingKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new UlocApiBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/doctrine.yaml');
    }
}
