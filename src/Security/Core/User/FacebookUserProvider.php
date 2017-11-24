<?php

namespace MinimalOriginal\SocialConnectBundle\Security\Core\User;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use MinimalOriginal\SocialConnectBundle\Security\Core\User\FacebookUserInterface;

class FacebookUserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username or Email "%s" does not exist.', $username));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function createUserWithToken(TokenInterface $token)
    {
    }

    /**
     * @param TokenInterface $token
     */
    public function loadUserWithToken(TokenInterface $token)
    {
        try{
          $user = $this->loadUserByUsername($token->getUsername());
        } catch(UsernameNotFoundException $e) {
          $facebookUser = $token->getFacebookUser();
          $user = $this->userManager->createUser();

          if( $user instanceof FacebookUserInterface ){
            $user->setEmail($facebookUser['email']);
            $user->setUsername($facebookUser['name']);
            $user->setEnabled(true);
            $user->setPlainPassword('mqjdsfkldqjsfmlkjqdsfmlkdqjs');
            $this->userManager->updatePassword($user);
            $this->userManager->updateUser($user);
            $token->setUser($user);
          }

        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf('Expected an instance of FOS\UserBundle\Model\UserInterface, but got "%s".', get_class($user)));
        }

        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Expected an instance of %s, but got "%s".', $this->userManager->getClass(), get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->findUserBy(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf('User with ID "%s" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }
}
