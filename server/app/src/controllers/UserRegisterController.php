<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\ClientException;
use petitphotobox\exceptions\AccessDeniedError;
use petitphotobox\model\documents\UserRegisterDocument;
use petitphotobox\model\records\DbUser;
use soloproyectos\text\Text;

class UserRegisterController extends Controller
{
  private $_document;

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
   * @return UserRegisterDocument
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
    $rePassword = $this->getParam("rePassword");

    if (UserAuth::isLogged($this->db)) {
      throw new AccessDeniedError("The user has already logged");
    }

    $this->_document = new UserRegisterDocument(
      $username, $password, $rePassword
    );
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
    $rePassword = $this->_document->getRePassword();

    if (   Text::isEmpty($username)
        || Text::isEmpty($password)
        || Text::isEmpty($rePassword)
    ) {
      throw new ClientException(
        "The following fields are required: username, password, rePassword"
      );
    }

    $user = DbUser::searchByName($this->db, $username);
    if ($user !== null) {
      throw new ClientException("The user already exist");
    }

    if (strlen($password) < MIN_PASSWORD_LENGTH) {
      throw new ClientException(
        "Password must have at least " . MIN_PASSWORD_LENGTH . " characters"
      );
    }

    if ($password !== $rePassword) {
      throw new ClientException("Passwords do not match");
    }

    UserAuth::create($this->db, $username, $password);
    UserAuth::login($this->db, $username, $password);
  }
}
