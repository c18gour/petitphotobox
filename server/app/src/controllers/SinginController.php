<?php
namespace petitphotobox\controllers;
use petitphotobox\auth\User;
use petitphotobox\controller\BaseController;
use petitphotobox\exception\ClientException;
use petitphotobox\exceptions\AuthException;
use petitphotobox\exceptions\DbError;
use soloproyectos\text\Text;

class SinginController extends BaseController
{
  // TODO: shouldn't be this in a config file?
  private $_minPasswordLength = 6;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->on("POST", [$this, "onPost"]);
    $this->apply();
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPost()
  {
    // TODO: veirficar que el usuario no existe (duplicado)
    $username = $this->getParam("username");
    $password = $this->getParam("password");
    $rePassword = $this->getParam("re_password");

    if (Text::isEmpty($username) ||
        Text::isEmpty($password) ||
        Text::isEmpty($rePassword)) {
      return $this->clientException(
        "The following fields are required: username, password, re_password"
      );
    }

    if (strlen($password) < $this->_minPasswordLength) {
      return $this->clientException(
        "Password must have at least {$this->_minPasswordLength} characters"
      );
    }

    if ($password !== $rePassword) {
      return $this->clientException("Passwords do not match");
    }

    try {
      $user = User::create($username, $password);
    } catch (DbError $e) {
      $this->appError($e);
    }

    try {
      User::login($username, $password);
    } catch (AuthException $e) {
      return $this->clientException($e);
    } catch (DbError $e) {
      $this->appError($e);
    }
  }
}
