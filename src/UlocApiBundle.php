<?php

namespace Uloc\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Uloc\ApiBundle\DependencyInjection\Compiler\PersonCompilerPass;
use Uloc\ApiBundle\DependencyInjection\UlocApiExtension;
use Uloc\ApiBundle\Manager\PersonManagerInterface;

class UlocApiBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new UlocApiExtension();
        }

        return $this->extension;
    }

    public function build(ContainerBuilder $container)
    {
        //parent::build($container);
        $container->addCompilerPass(new PersonCompilerPass());
        $container->registerForAutoconfiguration(PersonManagerInterface::class)
            ->addTag('uloc.person')
        ;

        #$this->addRegisterMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addRegisterMappingsPass(ContainerBuilder $container)
    {
        $mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine') => 'Uloc\ApiBundle\Entity',
        );
    }
}
