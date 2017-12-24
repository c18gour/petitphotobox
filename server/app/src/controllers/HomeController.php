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
    // TODO: move this to AuthController
    $this->_db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

    $categoryId = $this->getParam("category_id");
    $category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->_db, $categoryId);

    $this->_document = new HomeDocument($this->user, $category);
  }
}
