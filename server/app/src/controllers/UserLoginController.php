<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\BaseController;
use petitphotobox\core\exception\ClientException;
use soloproyectos\text\Text;

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
    $username = $this->getParam("username");
    $password = $this->getParam("password");

    if (Text::isEmpty($username) || Text::isEmpty($password)) {
      throw new ClientException("Required fields: username, password");
    }

    UserAuth::login($username, $password);
  }
}
