<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\model\documents\HomeDocument;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbUser;
use soloproyectos\db\DbConnector;
use soloproyectos\text\Text;

class HomeController extends AuthController
{
  private $_db;
  private $_user;
  private $_categoryId;
  private $_document;

  public function __construct()
  {
    parent::__construct();
    $this->_document = new HomeDocument();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return UserLoginDocument
   */
  public function getDocument()
  {
    return $this->_document;
  }

  public function onOpenRequest()
  {
    $this->_categoryId = $this->getParam("category_id");

    $this->_db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
    $this->_user = new DbUser($this->_db, $this->user->getId());

    // TODO: refactorize
    $this->_document->setCategories($this->_getCategoriesTree());
    $this->_document->setPictures($rows = $this->_getPictures());
  }

  private function _getCategoriesTree($parentCategory = null)
  {
    if ($parentCategory === null) {
      $parentCategory = $this->_user->getMainCategory();
    }

    return array_map(
      function ($category) {
        return [
          "id" => $category->getId(),
          "title" => $category->getTitle(),
          "items" => $this->_getCategoriesTree($category),
          "selected" => $category->getId() == $this->_categoryId
        ];
      },
      $parentCategory->getCategories()
    );
  }

  private function _getPictures()
  {
    $category = $this->_categoryId === null
      ? $this->user->getMainCategory()
      : new DbCategory($this->_db, $this->_categoryId);

    $pictures = $category->getPictures();

    return array_map(
      function ($picture) {
        $snapshot = $picture->getMainSnapshot();

        return ["id" => $picture->getId(), "path" => $snapshot->getPath()];
      },
      $pictures
    );
  }
}
