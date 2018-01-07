<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbCategoryPicture;
use soloproyectos\text\Text;

class PictureUpController extends AuthController
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

    // moves the record one position 'up'
    $next = $row->getNextRecord();
    if ($next != null) {
      $row->swap($next);
    }
  }
}
