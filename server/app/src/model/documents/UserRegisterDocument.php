<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class UserRegisterDocument extends BaseDocument
{
  private $_username;
  private $_password;
  private $_rePassword;

  /**
   * Creates an instance.
   */
  public function __construct($username, $password, $rePassword)
  {
    $this->_username = $username;
    $this->_password = $password;
    $this->_rePassword = $rePassword;
  }

  /**
   * Gets the username.
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->_username;
  }

  /**
   * Gets the password.
   *
   * @return string
   */
  public function getPassword()
  {
    return $this->_password;
  }

  /**
   * Gets the password.
   *
   * @return string
   */
  public function getRePassword()
  {
    return $this->_rePassword;
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    return [
      "username" => $this->_username,
      "password" => "",
      "rePassword" => ""
    ];
  }
}
