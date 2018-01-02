<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class UserLoginDocument extends BaseDocument
{
  private $_username;
  private $_password;

  /**
   * Creates an instance.
   *
   * @param string $username Username
   * @param string $password Password
   */
  public function __construct($username, $password)
  {
    $this->_username = $username;
    $this->_password = $password;
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
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    return [
      "username" => $this->_username,
      "password" => ""
    ];
  }
}
