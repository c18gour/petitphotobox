<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\CategoryDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\text\Text;

class CategoryNewController extends AuthController
{
  private $_document;
  private $_record;
  private $_parent;

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
    $parentId = $this->getParam("parentCategoryId");

    $this->_parent = Text::isEmpty($parentId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $parentId);
    if (!$this->_parent->isFound()) {
      throw new AppError("Parent category not found");
    }

    $this->_record = new DbCategory($this->db, $this->user);
    $this->_document = new CategoryDocument(
      $this->_record, $this->user->getMainCategory(), $this->_parent);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $title = $this->getParam("title");

    if (Text::isEmpty($title)) {
      throw new ClientException("Title is required");
    }

    $this->_record->parentCategoryId = $this->_parent->getId();
    $this->_record->title = $title;
    $this->_record->save();
  }
}
