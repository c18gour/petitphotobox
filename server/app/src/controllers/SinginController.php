<?php
namespace petitphotobox\controllers;
use petitphotobox\auth\User;
use petitphotobox\controller\BaseController;
use petitphotobox\exception\ClientException;
use petitphotobox\exceptions\AuthException;
use soloproyectos\text\Text;

class SinginController extends BaseController
{
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
    // TODO: deberíamos eliminar los espacios? Text::trim($str)
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

    $user = User::searchByName($username);
    if ($user !== null) {
      return $this->clientException("The user already exist");
    }

    if (strlen($password) < MIN_PASSWORD_LENGTH) {
      return $this->clientException(
        "Password must have at least " . MIN_PASSWORD_LENGTH . " characters"
      );
    }

    if ($password !== $rePassword) {
      return $this->clientException("Passwords do not match");
    }

    $user = User::create($username, $password);
    User::login($username, $password);
  }
}
