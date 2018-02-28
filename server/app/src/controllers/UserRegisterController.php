<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\model\Document;

class UserRegisterController extends Controller
{
  private $_isNewUser = false;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onGetRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    // TODO: language should be in the database
    return new Document(
      [
        "isNewUser" => $this->_isNewUser
      ]
    );
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

    $this->_isNewUser = UserAuth::login($this->db, $code, $state);
  }
}
