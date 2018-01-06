<?php
namespace petitphotobox\core\controller;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\Controller;
use petitphotobox\exceptions\SessionError;

abstract class AuthController extends Controller
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
      // retrieves the user from the current session
      $this->user = UserAuth::getInstance($this->db);
      if ($this->user === null) {
        throw new SessionError("Your session has expired");
      }
    });
  }
}
