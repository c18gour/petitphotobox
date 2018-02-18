<?php
namespace petitphotobox\controllers;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\auth\SystemAuth;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\model\Document;
use petitphotobox\exceptions\SessionError;
use soloproyectos\text\Text;

class UserVerifyController extends Controller
{
  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onGetRequest"]);
  }

  public function onGetRequest()
  {
    $code = $this->getParam("code");
    $state = $this->getParam("state");

    if (Text::isEmpty($code) || Text::isEmpty($state)) {
      throw new SessionError("The request wasn't well formed");
    }

    try {
      $token = SystemAuth::getAccessToken($code, $state);
    } catch (DropboxClientException $e) {
      throw new AppError($e->getMessage());
    }

    UserAuth::login($this->db, $token);
  }
}
