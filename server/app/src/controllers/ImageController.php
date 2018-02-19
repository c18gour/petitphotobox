<?php
namespace petitphotobox\controllers;
// TODO: remove image-resize package
use Gumlet\ImageResize;
use petitphotobox\core\auth\SystemAuth;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use soloproyectos\sys\file\SysFile;
use soloproyectos\text\Text;

class ImageController extends AuthController
{
  private $_smallImage;
  private $_path;

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
    $contents = $this->_smallImage
      ? SystemAuth::getThumbnailContents($this->user, $this->_path)
      : SystemAuth::getImageContents($this->user, $this->_path);

    return $contents;
  }

  public function onOpenRequest()
  {
    $this->_smallImage = $this->existParam("small");
    $this->_path = $this->getParam("path");

    if (Text::isEmpty($this->_path)) {
      throw new AppError("Path is required");
    }
  }
}
