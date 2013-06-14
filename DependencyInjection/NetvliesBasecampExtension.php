<?php

namespace Netvlies\Bundle\BasecampBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NetvliesBasecampExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('netvlies_basecamp.client');
        if ($config['authentication'] === 'http') {
            $params = array(
                'username' => $config['identification']['username'],
                'password' => $config['identification']['password'],
                'user_id'  => $config['identification']['user_id']
            );
        }
        if ($config['authentication'] === 'oauth') {
            $loader->load('oauth.xml');
            $definition->setFactoryClass(null);
            $definition->setFactoryService('netvlies_basecamp.oauth.factory');
            $factory = $container->getDefinition('netvlies_basecamp.oauth.factory');
            $factory->replaceArgument(0, new Reference($config['oauth']['credentials_provider']));
        }
        $params['app_name'] = $config['app_name'];
        $params['app_contact'] = $config['app_contact'];
        $params['auth'] = $config['authentication'];


        $definition->replaceArgument(0, $params);
    }
}
