<?php
namespace MinimalOriginal\SocialConnectBundle\Security\Core\User;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
  /**
   * Sets the plain password.
   *
   * @param string $password
   *
   * @return self
   */
  public function setPlainPassword($password);

  /**
   * Sets the username.
   *
   * @param string $username
   *
   * @return self
   */
  public function setUsername($username);

  /**
   * Sets the email.
   *
   * @param string $email
   *
   * @return self
   */
  public function setEmail($email);

  /**
   * @param bool $boolean
   *
   * @return self
   */
  public function setEnabled($boolean);


}
