<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\model\Document;
use soloproyectos\text\Text;

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

    if (Text::isEmpty($code) || Text::isEmpty($state)) {
      UserAuth::login($this->db, $code, $state);
    }
  }
}
