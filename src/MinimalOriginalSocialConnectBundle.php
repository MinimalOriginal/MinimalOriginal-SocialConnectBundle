<?php

namespace MinimalOriginal\SocialConnectBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use MinimalOriginal\SocialConnectBundle\DependencyInjection\Security\Factory\FacebookFactory;

class MinimalOriginalSocialConnectBundle extends Bundle
{
  public function build(ContainerBuilder $container)
   {
       parent::build($container);

       $extension = $container->getExtension('security');
       $extension->addSecurityListenerFactory(new FacebookFactory());

   }
}
