<?php
namespace petitphotobox\controllers;
use petitphotobox\controller\AuthController;

class LogoutController extends AuthController
{
  public function __construct()
  {
    parent::__construct();
    $this->on("POST", [$this, "onPost"]);
    $this->apply();
  }

  public function onPost()
  {
    $this->user->logout();
  }
}
