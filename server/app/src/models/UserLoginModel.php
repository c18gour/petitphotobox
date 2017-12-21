<?php
namespace petitphotobox\models;
use petitphotobox\core\model\BaseModel;

class UserLoginModel extends BaseModel
{
  public $uername;
  public $password;

  /**
   * Creates an instance.
   *
   * @param string $username User name
   * @param string $password Password
   */
  public function __construct($username = "", $password = "")
  {
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * {@inheritdoc}
   *
   * @return object Plain object
   */
  public function toObject()
  {
    return (object)["username" => $this->username, "password" => ""];
  }
}
