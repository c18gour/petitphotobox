<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbPicture;
use soloproyectos\text\Text;

class PictureDeleteController extends AuthController
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
    $pictureId = $this->getParam("pictureId");

    if (Text::isEmpty($pictureId)) {
      throw new ClientException("requiredFields");
    }

    $picture = new DbPicture($this->db, $this->user, $pictureId);
    if (!$picture->isFound()) {
      throw new ClientException("pictureNotFound");
    }

    $picture->delete();
  }
}
