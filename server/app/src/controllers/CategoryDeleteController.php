<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\EmptyDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\text\Text;

class CategoryDeleteController extends AuthController
{
  private $_document;

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
   * @return EmptyDocument
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
    $this->_document = new EmptyDocument();
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $id = $this->getParam("categoryId");
    if (Text::isEmpty($id)) {
      throw new ClientException("Category ID is required");
    }

    $category = new DbCategory($this->db, $this->user, $id);
    if (!$category->isFound()) {
      throw new ClientException("Category not found");
    }

    if ($category->isMain()) {
      throw new ClientException("Main category cannot be deleted");
    }

    $category->delete();
  }
}
