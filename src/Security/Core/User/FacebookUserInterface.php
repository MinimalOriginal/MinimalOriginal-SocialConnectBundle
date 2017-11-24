<?php
namespace MinimalOriginal\SocialConnectBundle\Security\Core\User;

interface FacebookUserInterface extends UserInterface
{

    /**
     * @param string $facebookId
     * @return FacebookUserInterface
     */
    public function setFacebookId($facebookId);

    /**
     * @return string
     */
    public function getFacebookId();

}
