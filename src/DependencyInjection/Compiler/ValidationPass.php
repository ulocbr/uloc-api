<?php

namespace Uloc\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers the additional validators according to the storage.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ValidationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $storage = 'orm';
        $validationFile = __DIR__.'/../../Resources/config/storage-validation/'.$storage.'.xml';

        $container->getDefinition('validator.builder')
            ->addMethodCall('addXmlMapping', [$validationFile]);
    }
}