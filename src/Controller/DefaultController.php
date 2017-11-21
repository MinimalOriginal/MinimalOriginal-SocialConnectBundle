<?php

namespace MinimalOriginal\SocialConnectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MinimalOriginalSocialConnectBundle:Default:index.html.twig');
    }
}
