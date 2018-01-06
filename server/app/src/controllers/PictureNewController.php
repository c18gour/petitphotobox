<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbCategoryPicture;
use soloproyectos\text\Text;

class PictureNewController extends AuthController
{
  private $_category;
  private $_record;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();
    $picture = $this->_record->getPicture();

    return new Document(
      [
        "id" => $this->_record->getId(),
        "title" => $picture->title,
        "categoryId" => $this->_category->getId(),
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
    $categoryId = $this->getParam("categoryId");
    if (Text::isEmpty($categoryId)) {
      throw new AppError("Category ID is required");
    }

    $this->_category = new DbCategory($this->db, $this->user, $categoryId);
    if (!$this->_category->isFound()) {
      throw new AppError("Category not found");
    }

    $this->_record = new DbCategoryPicture($this->db, $this->user);
  }
}
