<?php

namespace Uloc\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('uloc_api');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('public_key')
                    ->info('The key used to sign tokens (useless for HMAC). If not set, the key will be automatically computed from the secret key.')
                    ->defaultValue('%kernel.project_dir%/var/jwt/public.pem')
                ->end()
                ->scalarNode('secret_key')
                    ->info('The key used to sign tokens. It can be a raw secret (for HMAC), a raw RSA/ECDSA key or the path to a file itself being plaintext or PEM.')
                    ->defaultValue('%kernel.project_dir%/var/jwt/private.pem')
                ->end()
                ->scalarNode('pass_phrase')
                    ->info('The key passphrase (useless for HMAC)')
                    ->defaultValue('')
                ->end()
                ->scalarNode('token_ttl')
                    ->defaultValue(3600)
                ->end()
                ->scalarNode('clock_skew')
                    ->defaultValue(0)
                ->end()
                ->arrayNode('encoder')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('uloc_api.encoder.lcobucci')
                        ->end()
                        ->scalarNode('signature_algorithm')
                            ->defaultValue('RS256')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('crypto_engine')
                            ->defaultValue('openssl')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('logger')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('uloc_api.logger.default')
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('config_service_default')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('uloc_api.config_service.default')
                        ->end()
                    ->end()
                ->end()

                ->scalarNode('user_identity_field')
                    ->defaultValue('username')
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}