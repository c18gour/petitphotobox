<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbCategoryPicture;
use soloproyectos\text\Text;

class PictureDeleteController extends AuthController
{
  /**
   * Creates a new instance..
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
    $id = $this->getParam("id");
    if (Text::isEmpty($id)) {
      throw new ClientException("ID is required");
    }

    $row = new DbCategoryPicture($this->db, $this->user, $id);
    if (!$row->isFound()) {
      throw new ClientException("Record not found");
    }

    $row->delete();
  }
}
