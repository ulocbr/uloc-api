<?php

namespace Uloc\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Uloc\ApiBundle\Services\Message\MessageTransmissor;

/**
 * Registers the additional validators according to the storage.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class MessageTransmissorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(MessageTransmissor::class)) {
            return;
        }

        $definition = $container->findDefinition(MessageTransmissor::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('uloc.messenger_transmissor');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the TransportChain service
            //$definition->addMethodCall('addTransmissor', [new Reference($id)]);
            foreach ($tags as $attributes) {
                #dump($id);
                #dump($attributes);
                if (isset($attributes['alias'])) {
                    #dump($id);
                    #dump($attributes['alias']);
                    $definition->addMethodCall('addTransmissor', [
                        new Reference($id),
                        $attributes['alias']
                    ]);
                }
            }
        }
    }
}