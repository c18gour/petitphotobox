<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\records\DbCategory;
use soloproyectos\text\Text;

class CategoryDeleteController extends AuthController
{
  /**
   * Creates a new instance..
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
