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
      throw new AppError("requiredFields");
    }

    $this->_category = new DbCategory($this->db, $this->user, $id);
    if (!$this->_category->isFound()) {
      throw new ClientException("categoryNotFound");
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
      throw new ClientException("requiredFields");
    }

    if ($this->_category->isMain()) {
      throw new ClientException("categoryEdit.mainCategoryCannotBeEdited");
    }

    $parent = Text::isEmpty($parentId)
      ? $this->_category->getParent()
      : new DbCategory($this->db, $this->user, $parentId);
    if (!$parent->isFound()) {
      throw new ClientException("parentCategoryNotFound");
    }

    if ($this->_category->getId() == $parentId) {
      throw new ClientException("categoryEdit.categoryCannotBeParentOfItself");
    }

    // prevents from duplicate categories
    $category = DbCategory::searchByTitle(
      $this->db, $this->user, $parent->getId(), $title
    );
    if ($category != null && $category->getId() != $this->_category->getId()) {
      throw new ClientException("categoryEdit.duplicateCategory");
    }

    $this->_category->parentCategoryId = $parent->getId();
    $this->_category->title = $title;
    $this->_category->save();
  }
}
