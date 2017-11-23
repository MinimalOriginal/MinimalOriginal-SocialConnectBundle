MinimalOriginal Social Connect Bundle
========

Register bundle
========
$bundles = [
    ...
    new MinimalOriginal\SocialConnectBundle\MinimalOriginalSocialConnectBundle()(),
];

Register routes
========
minimal_original_social_connect:
    resource: "@MinimalOriginalSocialConnectBundle/Resources/config/routing.yml"
    prefix:   /


User entity
========

use MinimalOriginal\SocialConnectBundle\Entity\Traits\UserFacebookConnectTrait;

class User
{
  use UserFacebookConnectTrait;
  ...
}


Configuration
========
minimal_original_social_connect:
  auth:
    facebook:
      id: '%facebook_app_id%'
      secret: '%facebook_app_secret%'
