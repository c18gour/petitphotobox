<?php
namespace petitphotobox\controllers;
use petitphotobox\model\UserModel;
use petitphotobox\controller\BaseController;

class UserLoginController extends BaseController
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
    $username = trim($this->getParam("username"));
    $password = trim($this->getParam("password"));

    UserModel::login($username, $password);
  }
}
