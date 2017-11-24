<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Facebook\GraphNodes\GraphUser;

class FacebookUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;
    public $accessToken;
    public $facebookUser;

    public function __construct($accessToken = null, array $roles = array() )
    {
        parent::__construct($roles);

        $this->accessToken = $accessToken;

        // If the user has roles, consider it authenticated
        $this->setAuthenticated((count($roles) > 0) && (null !== $this->accessToken));


    }

    /**
     * Sets the Facebook user in the token.
     *
     * @param GraphUser $facebookUser The user
     *
     */
    public function setFacebookUser(GraphUser $facebookUser)
    {
        $this->facebookUser = $facebookUser;
    }

    /**
     * Returns the Facebook user of the token.
     *
     * @return GraphUser
     *
     */
    public function getFacebookUser()
    {
        return $this->facebookUser;
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
