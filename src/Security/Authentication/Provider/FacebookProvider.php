<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Authentication\Provider;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use MinimalOriginal\SocialConnectBundle\Security\Authentication\Token\SocialConnectUserToken;

class FacebookProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cachePool;

    public function __construct(UserProviderInterface $userProvider, CacheItemPoolInterface $cachePool)
    {
        $this->userProvider = $userProvider;
        $this->cachePool = $cachePool;
    }

    public function authenticate(TokenInterface $token)
    {

        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        $accessToken = $token->getAccessToken();

        if ($user) {
            $authenticatedToken = new SocialConnectUserToken($accessToken, $user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The Facebook authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof SocialConnectUserToken;
    }
}
