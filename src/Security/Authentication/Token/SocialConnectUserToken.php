<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class SocialConnectUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;
    public $accessToken;

    public function __construct($accessToken = null, array $roles = array() )
    {
        parent::__construct($roles);

        $this->accessToken = $accessToken;

        // If the user has roles, consider it authenticated
        $this->setAuthenticated((count($roles) > 0) && (null !== $this->accessToken));


    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getCredentials()
    {
        return '';
    }
}
