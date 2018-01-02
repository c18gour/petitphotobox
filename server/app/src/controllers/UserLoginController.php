<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\BaseController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\exceptions\SessionError;
use petitphotobox\model\documents\UserLoginDocument;
use soloproyectos\text\Text;

class UserLoginController extends BaseController
{
  private $_document;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addOpenRequestHandler([$this, "onGetRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return UserLoginDocument
   */
  public function getDocument()
  {
    return $this->_document;
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $username = $this->getParam("username", "");
    $password = $this->getParam("password");

    $this->_document = new UserLoginDocument($username, $password);
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onGetRequest()
  {
    if (UserAuth::isLogged($this->db)) {
      throw new SessionError("User has already logged");
    }
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $username = $this->_document->getUsername();
    $password = $this->_document->getPassword();

    if (Text::isEmpty($username) || Text::isEmpty($password)) {
      throw new ClientException("Username and Password are required");
    }

    UserAuth::login($this->db, $username, $password);
  }
}
