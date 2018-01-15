<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use soloproyectos\text\Text;

class CategoryEditController extends AuthController
{
  private $_category;

  /**
   * Creates a new instance.
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
   * @return Document
   */
  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();
    $parentCategory = $this->_category->getParent();

    return new Document(
      [
        "id" => $this->_category->getId(),
        "title" => $this->_category->title,
        "parentCategoryId" => $parentCategory->getId(),
        "categories" => $mainCategory->getTree()
      ]
    );
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

    $this->_category = new DbCategory($this->db, $this->user, $id);
    if (!$this->_category->isFound()) {
      throw new ClientException("Category not found");
    }
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

    if ($this->_category->isMain()) {
      throw new ClientException("Main category cannot be edited");
    }

    $parent = Text::isEmpty($parentId)
      ? $this->_category->getParent()
      : new DbCategory($this->db, $this->user, $parentId);
    if (!$parent->isFound()) {
      throw new ClientException("Parent category not found");
    }

    $category = DbCategory::searchByTitle(
      $this->db, $this->user, $parent->getId(), $title
    );
    if ($category != null && $category->getId() != $this->_category->getId()) {
      throw new ClientException("The category already exist");
    }

    $this->_category->parentCategoryId = $parent->getId();
    $this->_category->title = $title;
    $this->_category->save();
  }
}
