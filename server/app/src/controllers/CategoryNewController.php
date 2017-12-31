<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\CategoryNewDocument;
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
   * @return CategoryEditDocument
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
      : new DbCategory($this->db, $parentId);
    if (Text::isEmpty($this->_parent->getId())) {
      throw new AppError("Parent category not found");
    }

    $this->_record = new DbCategory($this->db);
    $this->_document = new CategoryNewDocument(
      $this->_record, $this->_parent, $this->user->getMainCategory());
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

    $this->_record->setUser($this->user);
    $this->_record->setParent($this->_parent);
    $this->_record->setTitle($title);
    $this->_record->save();
  }
}
