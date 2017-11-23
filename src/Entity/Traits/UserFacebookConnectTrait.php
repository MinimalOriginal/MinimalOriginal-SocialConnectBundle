<?php
namespace MinimalOriginal\SocialConnectBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UserFacebookConnectTrait
{
    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @param string $facebookId
     * @return UserFacebookConnectTrait
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

}
