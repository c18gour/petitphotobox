<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
use soloproyectos\text\Text;

class PictureDeleteController extends AuthController
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
    $categoryId = $this->getParam("categoryId");
    $pictureId = $this->getParam("pictureId");

    if (Text::isEmpty($pictureId)) {
      throw new ClientException("Picture ID is required");
    }

    $category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $categoryId);
    if (!$category->isFound()) {
      throw new ClientException("Category not found");
    }

    $picture = new DbPicture($this->db, $this->user, $pictureId);
    if (!$picture->isFound()) {
      throw new ClientException("Picture not found");
    }

    $category->removePicture($picture);
  }
}
