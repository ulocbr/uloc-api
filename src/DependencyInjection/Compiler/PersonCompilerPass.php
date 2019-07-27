<?php

namespace Uloc\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Uloc\ApiBundle\Manager\PersonManager;

class PersonCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(PersonManager::class)) {
            return;
        }

        $definition = $container->findDefinition('uloc_api.manager.person_manager');

        // find all service IDs with the uloc.person tag
        $taggedServices = $container->findTaggedServiceIds('uloc.person');

        /*foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ChainTransport service
            $definition->addMethodCall('setXxx', array(new Reference($id)));
        }*/

    }

}