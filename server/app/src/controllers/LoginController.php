<?php
namespace petitphotobox\controllers;
use \Exception;
use petitphotobox\auth\User;
use petitphotobox\controller\BaseController;
use petitphotobox\exceptions\AuthException;

class LoginController extends BaseController
{

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->on("POST", [$this, "onPost"]);
    $this->apply();
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPost()
  {
    $username = $this->getParam("username");
    $password = $this->getParam("password");

    try {
      User::login($username, $password);
    } catch (AuthException $e) {
      return $this->setStatusException($e);
    }
  }
}
