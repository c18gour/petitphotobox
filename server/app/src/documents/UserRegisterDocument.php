<?php
namespace petitphotobox\documents;
use petitphotobox\core\model\document\BaseDocument;

class UserRegisterDocument extends BaseDocument
{
  /**
   * Creates an instance.
   */
  public function __construct()
  {
    $this->setProperty("username", "");
    $this->setProperty("password", "");
    $this->setProperty("rePassword", "");
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
   * Gets the password.
   *
   * @return string
   */
  public function getRePassword()
  {
    return $this->getProperty("rePassword");
  }

  /**
   * Sets the password.
   *
   * @param string $value Password
   *
   * @return void
   */
  public function setRePassword($value)
  {
    $this->setProperty("rePassword", $value);
  }

  // TODO: create a protected toObject() method and overwrite in the inherited class
  public function __toString()
  {
    return json_encode(
      [
        "status" => [
          "code" => $this->getStatusCode(),
          "message" => $this->getStatusMessage()
        ],
        "body" => (object) [
          "username" => $this->getUsername(),
          "pasword" => $this->getPassword()
        ]
      ]
    );
  }
}
