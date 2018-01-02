<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\EmptyDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\text\Text;

class CategoryDeleteController extends AuthController
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
   * @return CategoryDocument
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
    $id = $this->getParam("categoryId");
    if (Text::isEmpty($id)) {
      throw new AppError("Category ID is required");
    }

    $this->_record = new DbCategory($this->db, $id);
    $this->_document = new EmptyDocument();
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $mainCategory = $this->user->getMainCategory();
    if ($this->_record->getId() == $mainCategory->getId()) {
      throw new ClientException("Main category cannot be deleted");
    }

    DbCategory::delete($this->db, $this->_record->getId());
  }
}
