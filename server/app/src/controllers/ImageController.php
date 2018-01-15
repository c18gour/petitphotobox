<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use soloproyectos\sys\file\SysFile;
use soloproyectos\text\Text;

class ImageController extends AuthController
{
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
    return file_get_contents($this->_path);
  }

  public function onOpenRequest()
  {
    $path = $this->getParam("path");

    if (Text::isEmpty($path)) {
      throw new AppError("Path is required");
    }

    $this->_path = SysFile::concat(USER_DATA_DIR, $this->user->username, $path);
    if (!is_file($this->_path)) {
      throw new AppError("Asset not found");
    }
  }
}
