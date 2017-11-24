<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Authentication\Provider;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use MinimalOriginal\SocialConnectBundle\Security\Authentication\Token\FacebookUserToken;

use FOS\UserBundle\Model\UserManagerInterface;

class FacebookAuthenticationProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserWithToken($token);
        $accessToken = $token->getAccessToken();

        if($user) {
            $authenticatedToken = new FacebookUserToken($accessToken, $user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }

        throw new AuthenticationException('The Facebook authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof FacebookUserToken;
    }
}
