<?php
namespace petitphotobox\core\controller;
use petitphotobox\models\UserModel;
use petitphotobox\core\controller\BaseController;
use petitphotobox\exceptions\SessionError;

class AuthController extends BaseController
{
  public $user;

  /**
   * Constructor.
   */
  public function __construct()
  {
    parent::__construct();

    // processes the initial request
    $this->addOpenRequestHandler(function () {
      $this->user = UserModel::getInstance();

      if ($this->user === null) {
        throw new SessionError("Your session has expired");
      }
    });
  }
}
