<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\EmptyDocument;
use petitphotobox\model\records\DbCategoryPicture;
use soloproyectos\text\Text;

class CategoryPictureDeleteController extends AuthController
{
  private $_document;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return EmptyDocument
   */
  public function getDocument()
  {
    return $this->_document;
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $this->_document = new EmptyDocument();
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
