<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\BaseController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\documents\UserLoginDocument;
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
    $this->_document = new UserLoginDocument();
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
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $this->_document->setUsername($this->getParam("username"));
    $this->_document->setPassword($this->getParam("password"));

    if ( Text::isEmpty($this->_document->getUsername())
      || Text::isEmpty($this->_document->getPassword())
    ) {
      throw new ClientException("Required fields: username, password");
    }

    UserAuth::login(
      $this->_document->getUsername(),
      $this->_document->getPassword()
    );
  }
}
