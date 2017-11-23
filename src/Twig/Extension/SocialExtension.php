<?php
namespace MinimalOriginal\SocialConnectBundle\Twig\Extension;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Facebook\Facebook;
/**
 * SocialExtension.
 *
 */
class SocialExtension extends \Twig_Extension
{
    private $facebook_app_id;
    private $facebook_app_secret;
    private $urlGenerator;

    /**
     * Constructor
     *
     * @param string $facebook_app_id
     * @param string $facebook_app_secret
     * @param UrlGenerator $urlGenerator
     *
     */
    public function __construct($facebook_app_id = null, $facebook_app_secret = null, UrlGeneratorInterface $urlGenerator)
    {
      $this->facebook_app_id = (string) $facebook_app_id;
      $this->facebook_app_secret = (string) $facebook_app_secret;
      $this->urlGenerator = $urlGenerator;
    }
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('facebook_connect_url', array($this, 'getFacebookConnectUrl')),
        );
    }

    /**
     * Returns Facebook connect url
     *
     * @return string
     */
    public function getFacebookConnectUrl()
    {

      $fb = new Facebook([
        'app_id' => $this->facebook_app_id,
        'app_secret' => $this->facebook_app_secret,
        'default_graph_version' => 'v2.2',
      ]);
      $helper = $fb->getRedirectLoginHelper();
      $permissions = ['email'];
      $fallback_url = $this->urlGenerator->generate('mo_facebook_fallback', array(), UrlGeneratorInterface::ABSOLUTE_URL);
      $loginUrl = $helper->getLoginUrl($fallback_url, $permissions);

      return $loginUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'minimal_original_social_connect';
    }

}
