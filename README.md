MinimalOriginal Social Connect Bundle
========

Register bundle
========
```
$bundles = [
    ...
    new MinimalOriginal\SocialConnectBundle\MinimalOriginalSocialConnectBundle(),
];
```

Register routes
========
Add the bundle routes which indludes Facebook fallback
```
mo_social_connect:
    resource: "@MinimalOriginalSocialConnectBundle/Resources/config/routing.yml"
```

User entity
========
The user entity have to implements the FacebookUserInterface (and which extends the Symfony's AdvancedUserInterface) because the FacebookUserProvider needs the followings methods :
⋅⋅* setEmail
⋅⋅* setUsername
⋅⋅* setEnabled
⋅⋅* setPlainPassword

```
use MinimalOriginal\SocialConnectBundle\Security\Core\User\FacebookUserInterface;

class User implements FacebookUserInterface
{
  ...
}
```

Configuration
========
```
minimal_original_social_connect:
  auth:
    facebook:
      id: '%facebook_app_id%'
      secret: '%facebook_app_secret%'
```
