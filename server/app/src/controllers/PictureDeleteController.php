<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\model\documents\EmptyDocument;
use petitphotobox\model\records\DbPicture;
use soloproyectos\text\Text;

class PictureDeleteController extends AuthController
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
    $id = $this->getParam("pictureId");
    if (Text::isEmpty($id)) {
      throw new AppError("Picture ID is required");
    }

    $this->_record = new DbPicture($this->db, $id);
    $owner = $this->_record->getOwner();
    if (
      !$this->_record->isFound() ||
      $owner == null ||
      $owner->getId() != $this->user->getId()
    ) {
      throw new AppError("Image not found");
    }

    $this->_document = new EmptyDocument();
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    DbPicture::delete($this->db, $this->_record->getId());
  }
}
