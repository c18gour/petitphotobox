<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;

class UserRegisterController extends Controller
{
  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onGetRequest"]);
  }

  /**
   * Processes GET requests.
   *
   * @return void
   */
  public function onGetRequest()
  {
    $code = $this->getParam("code");
    $state = $this->getParam("state");

    UserAuth::login($this->db, $code, $state);
  }
}
