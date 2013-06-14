<?php

namespace Netvlies\Bundle\BasecampBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('netvlies_basecamp');

        $rootNode
            ->children()
                ->scalarNode('authentication')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray(array('http', 'oauth'))
                        ->thenInvalid('Invalid authentication method "%s"')
                    ->end()
                ->end()
                ->arrayNode('identification')
                    ->children()
                        ->scalarNode('username')->end()
                        ->scalarNode('password')->end()
                        ->scalarNode('user_id')->end()
                    ->end()
                ->end()
                ->scalarNode('app_name')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('app_contact')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('oauth')
                    ->children()
                        ->scalarNode('credentials_provider')->end()
                    ->end()
                ->end()
            ->end()
            ->validate()
                ->ifTrue(function($v){return $v['authentication'] === 'http' && (! isset($v['identification']) || empty($v['identification']['username']) || empty($v['identification']['password']) || empty($v['identification']['user_id']));})
                    ->thenInvalid('You need to specify your user_id, username and password when using http authentication')
            ->end()
            ->validate()
                ->ifTrue(function($v) {return $v['authentication'] === 'oauth' && empty($v['oauth']['credentials_provider']);})
                    ->thenInvalid('You need to specify netvlies_basecamp.oauth.credentials_provider with the service id of your OAuth credentials provider')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
