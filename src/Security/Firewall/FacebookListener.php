<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use MinimalOriginal\SocialConnectBundle\Security\Authentication\Token\SocialConnectUserToken;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Facebook\Facebook;
use Facebook\Exceptions\{FacebookResponseException, FacebookSDKException};
use Facebook\Authentication\AccessToken;

class FacebookListener implements ListenerInterface
{
  protected $tokenStorage;
  protected $authenticationManager;
  protected $facebook_app_id;
  protected $facebook_app_secret;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $facebook_app_id = '', $facebook_app_secret = '' )
    {
      $this->tokenStorage = $tokenStorage;
      $this->authenticationManager = $authenticationManager;
      $this->facebook_app_id = $facebook_app_id;
      $this->facebook_app_secret = $facebook_app_secret;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if( 'facebook-fallback' !== $request->get('_route') ){
          return;
        }
        $fb = new Facebook([
          'app_id' => $this->facebook_app_id,
          'app_secret' => $this->facebook_app_secret,
          'default_graph_version' => 'v2.2',
          ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
          $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
          throw $e;
          exit;
        } catch(FacebookSDKException $e) {
          throw $e;
          exit;
        }

        if (! isset($accessToken)) {
          if ($helper->getError()) {
            throw new HttpException($helper->getErrorCode(), $helper->getError());
          } else {
            throw new HttpException(400, 'Bad request');
          }
          exit;
        }

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->facebook_app_id); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
          // Exchanges a short-lived access token for a long-lived one
          try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
          } catch (FacebookSDKException $e) {
            throw $e;
            exit;
          }

        }

        // Gets user infos
        try {
          // Returns a `Facebook\FacebookResponse` object
          $responseUser = $fb->get('/me?fields=id,name,email', (string) $accessToken);
          // enum{small, normal, album, large, square}
          $responsePicture = $fb->get('/me/picture?type=normal&redirect=false', (string) $accessToken);
        } catch(FacebookResponseException $e) {
          throw $e;
          exit;
        } catch(FacebookSDKException $e) {
          throw $e;
          exit;
        }

        $facebook_user = $responseUser->getGraphUser();

        $token = new SocialConnectUserToken($accessToken);
        $token->setUser($facebook_user['email']);
        try {
          $authToken = $this->authenticationManager->authenticate($token);
          $this->tokenStorage->setToken($authToken);
            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

    }

}