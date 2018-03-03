<?php
namespace petitphotobox\controllers;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\records\DbPicture;
use soloproyectos\text\Text;

class ThumbController extends AuthController
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
    $pictureId = $this->getParam("pictureId");

    if (Text::isEmpty($pictureId)) {
      throw new AppError("requiredFields");
    }

    $picture = new DbPicture($this->db, $this->user, $pictureId);
    if (!$picture->isFound()) {
      throw new AppError("pictureNotFound");
    }

    $snapshot = $picture->getMainSnapshot();
    $createdAt = strtotime($snapshot->createdAt);
    $path = preg_replace('/^images/', '', $snapshot->path);
    $etag = md5($path);

    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $createdAt)." GMT");
    header("Etag: $etag");

    if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $createdAt ||
      @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
      header("HTTP/1.1 304 Not Modified");
      die();
    }

    $account = $this->user->getAccount();

    $path = preg_replace('/^images/', '', $snapshot->path);

    try {
      $this->_imageContents = $account->getThumbnailContents(
        $this->user, $path
      );
    } catch (DropboxClientException $e) {
      throw new AppError($e->getMessage());
    }
  }
}
