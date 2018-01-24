<?php
namespace petitphotobox\controllers;
use Gumlet\ImageResize;
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
    $contents = "";

    if ($this->_smallImage) {
      $image = new ImageResize($this->_path);
      $image->resizeToWidth(THUMBNAIL_WIDTH);

      ob_start();
      $image->output();
      $contents = ob_get_clean();
    } else {
      $contents = file_get_contents($this->_path);
    }

    return $contents;
  }

  public function onOpenRequest()
  {
    $this->_smallImage = $this->existParam("small");
    $path = $this->getParam("path");

    if (Text::isEmpty($path)) {
      throw new AppError("Path is required");
    }

    $this->_path = SysFile::concat($this->user->getDir(), $path);
    if (!is_file($this->_path)) {
      throw new AppError("Image not found");
    }
  }
}
