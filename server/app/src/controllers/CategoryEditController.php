<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\CategoryDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\text\Text;

class CategoryEditController extends AuthController
{
  private $_document;
  private $_record;

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
    if (!$this->_record->isFound()) {
      throw new AppError("Category not found");
    }

    $this->_document = new CategoryDocument(
      $this->_record, $this->user->getMainCategory());
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $parentId = $this->getParam("parentCategoryId");
    $title = $this->getParam("title");

    if (Text::isEmpty($title)) {
      throw new ClientException("Title is required");
    }

    if ($this->_record->isMain()) {
      throw new ClientException("Main category cannot be edited");
    }

    $parent = Text::isEmpty($parentId)
      ? $this->_record->getParent()
      : new DbCategory($this->db, $parentId);
    if (!$parent->isFound()) {
      throw new ClientException("Parent category not found");
    }

    $this->_record->setUser($this->user);
    $this->_record->setParent($parent);
    $this->_record->setTitle($title);
    $this->_record->save();
  }
}
