<?php
namespace petitphotobox\controllers;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\auth\SystemAuth;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use soloproyectos\sys\file\SysFile;
use soloproyectos\text\Text;

class ImageController extends AuthController
{
  private $_imageContents;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    return $this->_imageContents;
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $smallImage = $this->existParam("small");
    $path = $this->getParam("path");

    if (Text::isEmpty($path)) {
      throw new AppError("Path is required");
    }

    try {
      $this->_imageContents = $smallImage
        ? SystemAuth::getThumbnailContents($this->user, $path)
        : SystemAuth::getImageContents($this->user, $path);
    } catch (DropboxClientException $e) {
      throw new AppError($e->getMessage());
    }
  }
}
