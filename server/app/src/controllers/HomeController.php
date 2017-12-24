<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\model\documents\HomeDocument;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbUser;
use soloproyectos\text\Text;

class HomeController extends AuthController
{
  private $_document;

  public function __construct()
  {
    parent::__construct();
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
    $categoryId = $this->getParam("category_id");
    $category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $categoryId);

    $this->_document = new HomeDocument($this->user, $category);
  }
}
