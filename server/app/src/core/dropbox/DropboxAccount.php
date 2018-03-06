<?php
namespace petitphotobox\core\dropbox;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Models\Account;
use petitphotobox\core\dropbox\DropboxService;
use soloproyectos\sys\file\SysFile;

class DropboxAccount
{
  private $_id;
  private $_token;

  /**
   * Constructor.
   *
   * Creates a new account based on the ID and Token returned by the
   * Dropbox's authentication system.
   *
   * @param string $id    Dropbox ID
   * @param string $token Dropbox token
   */
  public function __construct($id, $token)
  {
    $this->_id = $id;
    $this->_token = $token;
  }

  /**
   * Gets the Dropbox ID.
   *
   * @return string
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * Gets the Dropbox token.
   *
   * @return string
   */
  public function getToken()
  {
    return $this->_token;
  }

  /**
   * Gets the user's name.
   *
   * @return string
   */
  public function getName()
  {
    $account = $this->_getAccount();

    return $account->getDisplayName();
  }

  /**
   * Gets the user's email.
   *
   * @return string
   */
  public function getEmail()
  {
    $account = $this->_getAccount();

    return $account->getEmail();
  }

  /**
   * Gets information about the used and available space.
   *
   * @return integer[] [<used space in bytes>, <available space in bytes>]
   */
  public function getSpaceInfo()
  {
    $box = $this->_getBox();
    $info = $box->getSpaceUsage();
    $usedSpace = intval($info["used"]);
    $availSpace = intval($info["allocation"]["allocated"]);

    return [$usedSpace, $availSpace];
  }

  /**
   * Uploads a file to the user's account.
   *
   * @param string $localPath  Local path
   * @param string $remotePath Remote path
   *
   * @return string Remote path
   */
  public function upload($localPath, $remotePath)
  {
    $path = "/" . ltrim($remotePath, "/");
    $box = $this->_getBox();
    $file = new DropboxFile($localPath);

    $file = $box->upload($file, $path, ["autorename" => true]);

    return $file->getName();
  }

  // TODO: rename by loadImage();
  /**
   * Gets image contents.
   *
   * @param DbUser $user       User
   * @param string $remotePath Remote path
   *
   * @return string Image contents
   */
  public function getImageContents($user, $remotePath)
  {
    $path = "/" . ltrim($remotePath, "/");
    $box = $this->_getBox();
    $link = $box->getTemporaryLink($path);

    return file_get_contents($link->getLink());
  }

  // TODO: rename by loadThumbnail();
  /**
   * Gets thumbnail contents.
   *
   * @param DbUser $user       User
   * @param string $remotePath Remote path
   *
   * @return string Thumbnail contents
   */
  public function getThumbnailContents($user, $remotePath)
  {
    $path = "/" . ltrim($remotePath, "/");
    $box = $this->_getBox();
    $file = $box->getThumbnail($path, "large");

    return $file->getContents();
  }

  /**
   * Gets the Dropbox box.
   *
   * @return DropBox
   */
  private function _getBox()
  {
    $dpApp = new DropboxApp(
      DROPBOX_APP_KEY, DROPBOX_APP_SECRET, $this->_token
    );

    return new DropBox($dpApp);
  }

  /**
   * Gets the Dropbox user's account.
   *
   * @return Account
   */
  private function _getAccount()
  {
    $box = $this->_getBox();

    return $box->getCurrentAccount();
  }
}
