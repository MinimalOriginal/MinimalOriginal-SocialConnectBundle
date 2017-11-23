<?php

namespace MinimalOriginal\SocialConnectBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use MinimalOriginal\SocialConnectBundle\Security\Authentication\Provider\FacebookProvider;
use MinimalOriginal\SocialConnectBundle\Security\Firewall\FacebookListener;

class FacebookFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.wsse.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition(FacebookProvider::class))
            ->replaceArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.wsse.'.$id;
        $listener = $container
        ->setDefinition($listenerId, new ChildDefinition(FacebookListener::class));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'mo_facebook_connect';
    }

    public function addConfiguration(NodeDefinition $node)
    {
      // $node
      //   ->children()
      //     ->scalarNode('facebook_app_id')
      //         ->isRequired()
      //         ->cannotBeEmpty()
      //     ->end()
      //     ->scalarNode('facebook_app_secret')
      //         ->isRequired()
      //         ->cannotBeEmpty()
      //     ->end()
      //   ->end();
    }
}
