<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbCategory;
use soloproyectos\text\Text;

class CategoryDeleteController extends AuthController
{
  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addPostRequestHandler([$this, "onPostRequest"]);
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
      throw new ClientException("requiredFields");
    }

    $category = new DbCategory($this->db, $this->user, $id);
    if (!$category->isFound()) {
      throw new ClientException("categoryNotFound");
    }

    if ($category->isMain()) {
      throw new ClientException("categoryDelete.mainCategoryCannotBeDeleted");
    }

    $category->delete();
  }
}
