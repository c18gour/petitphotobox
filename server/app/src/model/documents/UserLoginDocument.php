<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class UserLoginDocument extends BaseDocument
{
  /**
   * Creates an instance.
   */
  public function __construct()
  {
    $this->setProperty("username", "");
    $this->setProperty("password", "");
  }

  /**
   * Gets the username.
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->getProperty("username");
  }

  /**
   * Sets the username.
   *
   * @param string $value Username
   *
   * @return void
   */
  public function setUsername($value)
  {
    $this->setProperty("username", $value);
  }

  /**
   * Gets the password.
   *
   * @return string
   */
  public function getPassword()
  {
    return $this->getProperty("password");
  }

  /**
   * Sets the password.
   *
   * @param string $value Password
   *
   * @return void
   */
  public function setPassword($value)
  {
    $this->setProperty("password", $value);
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    return [
      "username" => $this->getUsername(),
      "password" => ""
    ];
  }
}
