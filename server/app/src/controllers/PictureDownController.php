<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\EmptyDocument;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbPicture;
use soloproyectos\text\Text;

class PictureDownController extends AuthController
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
    $categoryId = $this->getParam("categoryId");
    $id = $this->getParam("pictureId");

    if (Text::isEmpty($categoryId) || Text::isEmpty($id)) {
      throw new ClientException("Category ID and Picture ID is required");
    }

    $category = new DbCategory($this->db, $categoryId);
    $user = $category->getUser();
    if (!$category->isFound() || $user->getId() != $this->user->getId()) {
      throw new ClientException("Category not found");
    }

    $picture = new DbPicture($this->db, $id);
    if (!$picture->isFound() || !$picture->isInCategory($category)) {
      throw new ClientException("Picture not found");
    }

    $category->movePictureDown($picture);
  }
}
