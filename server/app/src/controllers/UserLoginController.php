<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\dropbox\DropboxService;
use petitphotobox\core\model\Document;
use soloproyectos\text\Text;

// TODO: redirect to home if the user was already logged
class UserLoginController extends Controller
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
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return new Document(
      [
        "url" => DropboxService::getAuthUrl()
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

    if (!Text::isEmpty($code) || !Text::isEmpty($state)) {
      UserAuth::login($this->db, $code, $state);
    }
  }
}
