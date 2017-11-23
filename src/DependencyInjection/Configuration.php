<?php

namespace MinimalOriginal\SocialConnectBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('minimal_original_social_connect');

        $rootNode
            ->children()
                ->arrayNode('auth')
                    ->isRequired()
                    ->children()
                        ->arrayNode('facebook')
                            ->isRequired()
                            ->children()
                                ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
