<?php
namespace petitphotobox\controllers;
use petitphotobox\model\UserModel;
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
    $username = trim($this->getParam("username"));
    $password = trim($this->getParam("password"));

    if (Text::isEmpty($username) || Text::isEmpty($password)) {
      throw new ClientException("Required fields: username, password");
    }

    UserModel::login($username, $password);
  }
}
