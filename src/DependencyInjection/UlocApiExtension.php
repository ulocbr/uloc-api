<?php

namespace Uloc\ApiBundle\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Console\Application;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Uloc\ApiBundle\Manager\UserManagerInterface;
use Uloc\ApiBundle\Services\Config\ConfigServiceInterface;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

class UlocApiExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        // $loader->load('storage-validation/orm.xml');
        $loader->load('services.xml');
        $loader->load('lcobucci.xml');
        $loader->load('key_loader.xml');

        if (class_exists(Application::class)) {
            $loader->load('console.xml');
        }

        $loader->load('controller.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (empty($config['public_key']) && empty($config['secret_key'])) {
            throw new InvalidConfigurationException('You must either configure a "public_key" or a "secret_key".', 'uloc_api');
        }

        $container->setParameter('uloc_api.pass_phrase', $config['pass_phrase']);
        $container->setParameter('uloc_api.token_ttl', $config['token_ttl']);
        $container->setParameter('uloc_api.clock_skew', $config['clock_skew']);
        $container->setParameter('uloc_api.user_identity_field', $config['user_identity_field']);
        $encoderConfig = $config['encoder'];

        $container->setAlias('uloc_api.encoder', new Alias($encoderConfig['service'], true));
        $container->setAlias(JWTEncoderInterface::class, 'uloc_api.encoder');
        $container->setAlias(
            'uloc_api.key_loader',
            new Alias('uloc_api.key_loader.raw', true)
        );

        $loggerConfig = $config['logger'];

        $container->setAlias('uloc_api.logger', new Alias($loggerConfig['service'], true));
        $container->setAlias(LogInterface::class, 'uloc_api.logger');

        $configService = $config['config_service_default'];

        $container->setAlias('uloc_api.config_service', new Alias($configService['service'], true));
        $container->setAlias(ConfigServiceInterface::class, 'uloc_api.config_service');

        $container->setAlias(UserManagerInterface::class, 'uloc_api.manager.user_manager');

        $configMessegeHandler = $config['message_async_handler_default'];

        $container->setAlias('uloc_api.message_async_handler', new Alias($configMessegeHandler['service'], true));
        $container->setAlias(MessageHandlerInterface::class, 'uloc_api.message_async_handler');

        if ($configMessegeHandler['service'] === 'uloc_api.message_async_handler.default') {
            $container
                ->findDefinition('uloc_api.message_async_handler')
                ->addTag('messenger.message_handler');
        }

        $container
            ->findDefinition('uloc_api.key_loader')
            ->replaceArgument(0, $config['secret_key'])
            ->replaceArgument(1, $config['public_key']);

        $container
            ->findDefinition('uloc_api.token_get_command');
            // ->setArgument(0, EntityManagerInterface::class) # remove in 4.4
            // ->setArgument(1, JWTEncoderInterface::class); # remove in 4.4

        $container
            ->findDefinition('uloc_api.user_create_command');
            // ->setArgument(0, EntityManagerInterface::class) # remove in 4.4
            // ->setArgument(1, UserPasswordEncoderInterface::class); # remove in 4.4

        $container->setParameter('uloc_api.encoder.signature_algorithm', $encoderConfig['signature_algorithm']);
        $container->setParameter('uloc_api.encoder.crypto_engine', $encoderConfig['crypto_engine']);

        $container->findDefinition('uloc_api.object_manager')
            ->setFactory([new Reference('doctrine'), 'getManager']);
        #$container
        #    ->getDefinition('uloc_api.extractor.chain_extractor')
        #    ->replaceArgument(0, $this->createTokenExtractors($container, $config['token_extractors']));

    }

    public function getAlias()
    {
        return 'uloc_api';
    }
}
