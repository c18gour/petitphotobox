<?php
namespace petitphotobox\controllers;
use petitphotobox\controller\AuthController;

class UserLogoutController extends AuthController
{

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $this->user->logout();
  }
}
