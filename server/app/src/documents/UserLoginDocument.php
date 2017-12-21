<?php
namespace petitphotobox\documents;
use petitphotobox\core\model\document\BaseDocument;

class UserLoginDocument extends BaseDocument
{
  /**
   * Creates an instance.
   *
   * @param string $username User name
   * @param string $password Password
   */
  public function __construct($username = "", $password = "")
  {
    $this->setProperty("username", $username);
    $this->setProperty("password", $password);
  }

  public function getUsername()
  {
    return $this->getProperty("username");
  }

  public function setUsername($value)
  {
    $this->setProperty("username", $value);
  }

  public function getPassword()
  {
    return $this->getProperty("password");
  }

  public function setPassword($value)
  {
    $this->setProperty("password", $value);
  }

  public function __toString()
  {
    // hides the password
    $obj = json_decode(parent::__toString());
    $obj->body->password = "";

    return json_encode($obj);
  }
}
