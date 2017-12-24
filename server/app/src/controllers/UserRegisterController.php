<?php
namespace petitphotobox\controllers;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\controller\BaseController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbUser;
use soloproyectos\text\Text;

class UserRegisterController extends BaseController
{
  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $username = $this->getParam("username");
    $password = $this->getParam("password");
    $rePassword = $this->getParam("re_password");

    if (   Text::isEmpty($username)
        || Text::isEmpty($password)
        || Text::isEmpty($rePassword)
    ) {
      throw new ClientException(
        "The following fields are required: username, password, re_password"
      );
    }

    $user = DbUser::searchByName($username);
    if ($user !== null) {
      throw new ClientException("The user already exist");
    }

    if (strlen($password) < MIN_PASSWORD_LENGTH) {
      throw new ClientException(
        "Password must have at least " . MIN_PASSWORD_LENGTH . " characters"
      );
    }

    if ($password !== $rePassword) {
      throw new ClientException("Passwords do not match");
    }

    UserAuth::create($username, $password);
    UserAuth::login($username, $password);
  }
}
