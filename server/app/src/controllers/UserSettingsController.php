<?php
namespace petitphotobox\controllers;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\model\Document;
use soloproyectos\text\Text;

class UserSettingsController extends AuthController
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
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return new Document(
      [
        "language" => $this->getCookie("lang")
      ]
    );
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $language = $this->getParam("language");

    if (Text::isEmpty($language)) {
      throw new ClientException("Language is required");
    }

    $this->setCookie("lang", $language);
  }
}
