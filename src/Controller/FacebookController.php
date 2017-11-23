<?php

namespace MinimalOriginal\SocialConnectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpKernel\Exception\HttpException;

use Facebook\Facebook;
use Facebook\Exceptions\{FacebookResponseException, FacebookSDKException};
use Facebook\Authentication\AccessToken;

class FacebookController extends Controller
{
    public function fallbackAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
    public function oldfallbackAction(SessionInterface $session)
    {

      $facebook_app_id = (string) $this->container->getParameter('facebook.app.id');
      $facebook_app_secret = (string) $this->container->getParameter('facebook.app.secret');

      $fb = new Facebook([
        'app_id' => $facebook_app_id,
        'app_secret' => $facebook_app_secret,
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
      $tokenMetadata->validateAppId($facebook_app_id); // Replace {app-id} with your app id
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

      $userManager = $this->container->get('fos_user.user_manager');

      if( null !== ($user = $this->getUser()) ){
      }else if( null !== ($user = $userManager->findUserByEmail($facebook_user['email'])) ){
      }else{
        $user = $userManager->createUser();
        $user->setEmail($facebook_user['email']);
        $user->setUsername($facebook_user['name']);
      }
      $user->setFacebookAccessToken((string) $accessToken);
      $user->setFacebookId($facebook_user['id']);

      $userManager->updateUser($user);

      $referer = $this->getRequest()->headers->get('referer');

return $this->redirect($referer);
    }
}
