<?php
namespace petitphotobox\controller;
use petitphotobox\auth\User;
use petitphotobox\controller\BaseController;
use petitphotobox\exception\AppError;

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
    $this->on("OPEN", function () {
      try {
        $this->user = User::getInstance();
      } catch (AppError $e) {
        $this->appError($e);
      }
    });
  }
}
