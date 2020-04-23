<?php

namespace Uloc\ApiBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Uloc\ApiBundle\UlocApiBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

abstract class AbstractFuncionalTest extends TestCase
{
    /* @var \App\Kernel $kernel */
    public static $kernel = null;

    /* @var \Symfony\Component\DependencyInjection\Container $container */
    protected $container;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        if(null === self::$kernel) {
            self::$kernel = new UlocApiTestingKernel('test', true);
            self::$kernel->boot();
        }
        $this->container =  self::$kernel->getContainer();
    }
}

class UlocApiTestingKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new UlocApiBundle(),
            new FrameworkBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/doctrine.yaml');
    }
}
