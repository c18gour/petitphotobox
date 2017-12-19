<?php
namespace petitphotobox\controller;
use petitphotobox\model\User;
use petitphotobox\controller\BaseController;
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
      $this->user = User::getInstance();

      if ($this->user === null) {
        throw new SessionError("Your session has expired");
      }
    });
  }
}
