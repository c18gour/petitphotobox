<?php
namespace petitphotobox;
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
  }

  public function getResponse()
  {
    return [1, 2, 3];
  }
}
