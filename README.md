MinimalOriginal Social Connect Bundle
========

Register bundle
========
$bundles = [
    ...
    new MinimalOriginal\SocialConnectBundle\MinimalOriginalSocialConnectBundle(),
];

Register routes
========
mo_social_connect:
    resource: "@MinimalOriginalSocialConnectBundle/Resources/config/routing.yml"


User entity
========

use MinimalOriginal\SocialConnectBundle\Entity\User as BaseUser;

class User extends BaseUser
{
  ...
}
