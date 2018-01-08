<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
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
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();

    return new Document(
      [
        "id" => $this->_picture->getId(),
        "title" => $this->_picture->title
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
    $this->_picture = new DbPicture($this->db, $this->user);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $categoryId = $this->getParam("categoryId");
    $title = $this->getParam("title");

    if (Text::isEmpty($title)) {
      throw new ClientException("Title are required");
    }

    $category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $categoryId);

    if (!$category->isFound()) {
      throw new AppError("Category not found");
    }

    // creates a new picture
    $this->_picture->title = $title;
    // TODO: fix it
    $this->_picture->path = "/data/images/not-found.jpg";
    $this->_picture->save();

    // ...and adds it to the category
    $category->addPicture($this->_picture);
  }
}
