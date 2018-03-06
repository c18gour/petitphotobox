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
  protected $snapshot;

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
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
    $path = $this->getParam("path");
    $small = $this->existParam("small");

    if (Text::isEmpty($path)) {
      throw new AppError("Missing required fields");
    }

    $user = UserAuth::getInstance($db);
    if ($user === null) {
      throw new AppError("Your session has expired");
    }

    $this->snapshot = DbSnapshot::searchByPath($db, $user, $path);
    if ($this->snapshot === null) {
      throw new AppError("Image not found");
    }

    $createdAt = strtotime($this->snapshot->createdAt);
    $etag = md5($this->snapshot->path);

    CacheSystem::ifNotCached(
      $createdAt, $etag,
      function () use ($small) {
        try {
          $contents = $small
            ? $this->snapshot->loadThumbnail()
            : $this->snapshot->loadImage();

          echo $contents;
        } catch (DropboxClientException $e) {
          throw new AppError($e->getMessage());
        }
      }
    );
  }
}
