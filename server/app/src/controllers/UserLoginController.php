<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\exceptions\SessionError;
use soloproyectos\text\Text;

class UserLoginController extends Controller
{
  private $_username = "";

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onGetRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return new Document(
      [
        "username" => $this->_username
      ]
    );
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
    $this->_username = $this->getParam("username", "");
    $password = $this->getParam("password");

    if (Text::isEmpty($this->_username) || Text::isEmpty($password)) {
      throw new ClientException("Username and Password are required");
    }

    UserAuth::login($this->db, $this->_username, $password);
  }
}
