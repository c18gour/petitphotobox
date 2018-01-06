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
  private $_picture;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
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

    $this->_picture = new DbCategoryPicture($this->db, $this->user);
  }
}
