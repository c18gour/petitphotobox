<?php
namespace petitphotobox\controllers;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\auth\UserAuth;
use petitphotobox\core\cache\CacheSystem;
use petitphotobox\core\exception\AppError;
use petitphotobox\records\DbSnapshot;
use soloproyectos\db\DbConnector;
use soloproyectos\http\controller\HttpController;
use soloproyectos\text\Text;

class SnapshotController extends HttpController
{
  private $_db;
  private $_user;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onInit"]);
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function processRequest()
  {
    try {
      parent::processRequest();
    } catch (AppError $e) {
      header("HTTP/1.0 500 " . $e->getMessage());
      header("Content-Type: text/plain; charset=utf-8");

      $contents = file_get_contents(IMAGE_NOT_FOUND_PATH);
      echo $contents;
    }
  }

  /**
   * Variable initialization.
   *
   * @return void
   */
  public function onInit()
  {
    $this->_db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
    $this->_user = UserAuth::getInstance($this->_db);

    if ($this->_user === null) {
      throw new AppError("Your session has expired");
    }
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $path = $this->getParam("path");
    $small = $this->existParam("small");

    if (Text::isEmpty($path)) {
      throw new AppError("Missing required fields");
    }

    $snapshot = DbSnapshot::searchByPath($this->_db, $this->_user, $path);
    if ($snapshot === null) {
      echo $this->_loadImage($path, $small);
      return;
    }

    $createdAt = strtotime($snapshot->createdAt);
    $etag = md5($snapshot->path); // TODO: do not use path as md4 code
    CacheSystem::ifNotCached(
      $createdAt, $etag,
      function () use ($path, $small) {
        echo $this->_loadImage($path, $small);
      }
    );
  }

  /**
   * Loads an image from the Dropbox account.
   *
   * @param string  $path  Image path
   * @param boolean $small Is a thumbnail?
   *
   * @return [type]        [description]
   */
  private function _loadImage($path, $small)
  {
    $account = $this->_user->getAccount();
    $contents = $small
      ? $account->loadThumbnail($this->_user, $path)
      : $account->loadImage($this->_user, $path);

    return $contents;
  }
}
