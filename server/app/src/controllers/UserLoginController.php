<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\BaseController;
use petitphotobox\core\exception\ClientException;
use soloproyectos\text\Text;
use petitphotobox\models\UserLoginModel;

class UserLoginController extends BaseController
{
  private $_model;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $username = $this->getParam("username");
    $password = $this->getParam("password");
    $this->_model = new UserLoginModel($username, $password);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    if (   Text::isEmpty($this->_model->username)
        || Text::isEmpty($this->_model->password)
    ) {
      throw new ClientException("Required fields: username, password");
    }

    UserAuth::login($this->_model->username, $this->_model->password);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function printDocument()
  {
    $this->response->setBody($this->_model->toObject());
    parent::printDocument();
  }
}
