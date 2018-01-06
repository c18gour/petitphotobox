<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\model\documents\HomeDocument;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbUser;
use soloproyectos\text\Text;

class HomeController extends AuthController
{
  private $_document;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * NOTE: las cosas que tienen que ver deben estar cerca. Y, siguiendo esa
   * premisa, el documento no debería estar en una carpeta diferente, sino
   * en el propipio controlador. Por ejemplo:
   *   return new Document({... json object ...});
   *
   * @return HomeDocument
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
    $categoryId = $this->getParam("categoryId");
    $category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $categoryId);

    if (!$category->isFound()) {
      throw new AppError("Category not found");
    }

    $this->_document = new HomeDocument($this->user, $category);
  }
}
