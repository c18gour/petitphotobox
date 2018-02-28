<?php
namespace petitphotobox\core\dropbox;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use petitphotobox\core\dropbox\DropboxService;
use soloproyectos\sys\file\SysFile;

class DropboxAccount
{
  private $_id;
  private $_token;

  public function __construct($id, $token)
  {
    $this->_id = $id;
    $this->_token = $token;
  }

  public function getId()
  {
    return $this->_id;
  }

  public function getToken()
  {
    return $this->_token;
  }

  public function getName()
  {
    $account = $this->_getAccount();

    return $account->getDisplayName();
  }

  public function getEmail()
  {
    $account = $this->_getAccount();

    return $account->getEmail();
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

    return SysFile::concat(IMAGE_FOLDER, $file->getName());
  }

  private function _getBox()
  {
    $dpApp = new DropboxApp(
      DROPBOX_APP_KEY, DROPBOX_APP_SECRET, $this->_token
    );

    return new DropBox($dpApp);
  }

  private function _getAccount()
  {
    $box = $this->_getBox();

    return $box->getCurrentAccount();
  }
}
