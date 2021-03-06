<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use soloproyectos\text\Text;

class CategoryNewController extends AuthController
{
  private $_parentCategory;
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

    return new Document(
      [
        "id" => $this->_category->getId(),
        "title" => $this->_category->title,
        "parentCategoryId" => $this->_parentCategory->getId(),
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
    $parentId = $this->getParam("parentCategoryId");

    $this->_parentCategory = Text::isEmpty($parentId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $parentId);
    if (!$this->_parentCategory->isFound()) {
      throw new AppError("parentCategoryNotFound");
    }

    $this->_category = new DbCategory($this->db, $this->user);
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
      throw new ClientException("requiredFields");
    }

    // prevents from duplicate categories
    $category = DbCategory::searchByTitle(
      $this->db, $this->user, $this->_parentCategory->getId(), $title
    );
    if ($category != null) {
      throw new ClientException("categoryNew.duplicateCategory");
    }

    $this->_category->parentCategoryId = $this->_parentCategory->getId();
    $this->_category->title = $title;
    $this->_category->save();
  }
}
