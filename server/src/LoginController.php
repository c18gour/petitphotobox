<?php
namespace petitphotobox;
use petitphotobox\core\auth\User;
use petitphotobox\core\http\controller\BaseController;

class LoginController extends BaseController {

  public function __construct()
  {
    $this->on("POST", [$this, "onPost"]);
    $this->apply();
  }

  public function onPost()
  {
    $username = $this->getParam("username");
    $password = $this->getParam("password");

    User::login($username, $password);
  }

  /**
   * {@inheritDoc}
   */
  public function getResponse()
  {
    return ["status" => null];
  }
}
