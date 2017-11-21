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
