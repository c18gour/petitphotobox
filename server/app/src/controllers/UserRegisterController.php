<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\exceptions\AccessDeniedError;
use petitphotobox\records\DbUser;
use soloproyectos\text\Text;

class UserRegisterController extends Controller
{
  private $_username = "";

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
  public function onOpenRequest()
  {
    if (UserAuth::isLogged($this->db)) {
      // TODO: (low) shouldn't it be a SessionError?
      throw new AccessDeniedError("The user has already logged");
    }
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $this->_username = $this->getParam("username");
    $password = $this->getParam("password");
    $rePassword = $this->getParam("rePassword");

    if (   Text::isEmpty($this->_username)
        || Text::isEmpty($password)
        || Text::isEmpty($rePassword)
    ) {
      throw new ClientException(
        "The following fields are required: username, password, rePassword"
      );
    }

    $user = DbUser::searchByName($this->db, $this->_username);
    if ($user !== null) {
      throw new ClientException("The user already exist");
    }

    if ($password !== $rePassword) {
      throw new ClientException("Passwords do not match");
    }

    UserAuth::create($this->db, $this->_username, $password);
    UserAuth::login($this->db, $this->_username, $password);
  }
}
