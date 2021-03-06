<?php

namespace Uloc\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Uloc\ApiBundle\DependencyInjection\Compiler\MessageTransmissorPass;
use Uloc\ApiBundle\DependencyInjection\Compiler\PersonCompilerPass;
use Uloc\ApiBundle\DependencyInjection\Compiler\ValidationPass;
use Uloc\ApiBundle\DependencyInjection\UlocApiExtension;
use Uloc\ApiBundle\Manager\Model\CustomManagerInterface;
use Uloc\ApiBundle\Manager\PersonManagerInterface;
use Uloc\ApiBundle\Manager\UserManagerInterface;
use Uloc\ApiBundle\Services\Message\MessageTransmissor;

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
        $container->addCompilerPass(new ValidationPass());
        $container->addCompilerPass(new MessageTransmissorPass());
        $container->registerForAutoconfiguration(PersonManagerInterface::class)
            ->addTag('uloc.person');
        $container->registerForAutoconfiguration(UserManagerInterface::class)
            ->addTag('uloc.user');

        $container->registerForAutoconfiguration(MessageTransmissor::class)
            ->addTag('uloc.messenger_transmissor');
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
