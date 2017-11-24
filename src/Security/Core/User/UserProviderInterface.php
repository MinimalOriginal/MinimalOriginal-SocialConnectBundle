<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Core\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\User\UserProviderInterface as SymfonyUserProviderInterface;

interface UserProviderInterface extends SymfonyUserProviderInterface
{
    /**
     * Creates user from a token.
     *
     * @param string $token The token
     *
     * @return TokenInterface
     *
     */
    public function createUserWithToken(TokenInterface $token);

}
