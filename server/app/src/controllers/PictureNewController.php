<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\PictureDocument;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbCategoryPicture;
use soloproyectos\text\Text;

class PictureNewController extends AuthController
{
  private $_document;
  private $_record;

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
    $categoryId = $this->getParam("categoryId");
    if (Text::isEmpty($categoryId)) {
      throw new AppError("Category ID is required");
    }

    $category = new DbCategory($this->db, $this->user, $categoryId);
    if (!$category->isFound()) {
      throw new AppError("Category not found");
    }

    $this->_record = new DbCategoryPicture($this->db, $this->user);
    $this->_document = new PictureDocument($this->_record, $this->user->getMainCategory(), $category);
  }
}
