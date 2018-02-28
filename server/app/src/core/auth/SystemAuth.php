<?php
namespace petitphotobox\core\auth;
use Kunnu\Dropbox\Models\AccessToken;
use Kunnu\Dropbox\Authentication\DropboxAuthHelper;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use petitphotobox\records\DbUser;
use soloproyectos\sys\file\SysFile;

/**
 * Provides some methods to access the Dropbox user account.
 */
class SystemAuth
{
  /**
   * Gets authorization URL.
   *
   * @return string
   */
  public static function getUrl()
  {
    $helper = SystemAuth::_getAuthHelper();

    return $helper->getAuthUrl(CLIENT_REDIRECT_URL);
  }

  /**
   * Gets user's authetication token.
   *
   * @param string $code  Authentication code
   * @param string $state Authentication state
   *
   * @return AccessToken
   */
  public static function getAccessToken($code, $state)
  {
    $helper = SystemAuth::_getAuthHelper();

    return $helper->getAccessToken($code, $state, CLIENT_REDIRECT_URL);
  }

  /**
   * Uploads a file to the user's account.
   *
   * @param DbUser $user       User
   * @param string $localPath  Local path
   * @param string $remotePath Remote path
   *
   * @return string Remote path
   */
  public static function upload($user, $localPath, $remotePath)
  {
    $path = SystemAuth::prependSlash($remotePath);
    $account = SystemAuth::_getUserAccount($user);
    $dpFile = new DropboxFile($localPath);

    $file = $account->upload($dpFile, $path, ["autorename" => true]);

    return SysFile::concat(IMAGE_FOLDER, $file->getName());
  }

  /**
   * Gets image contents.
   *
   * @param DbUser $user       User
   * @param string $remotePath Remote path
   *
   * @return string Image contents
   */
  public static function getImageContents($user, $remotePath)
  {
    $path = SystemAuth::prependSlash($remotePath);
    $account = SystemAuth::_getUserAccount($user);
    $link = $account->getTemporaryLink($path);

    return file_get_contents($link->getLink());
  }

  /**
   * Gets thumbnail contents.
   *
   * @param DbUser $user       User
   * @param string $remotePath Remote path
   *
   * @return string Thumbnail contents
   */
  public static function getThumbnailContents($user, $remotePath)
  {
    $path = SystemAuth::prependSlash($remotePath);
    $account = SystemAuth::_getUserAccount($user);
    $file = $account->getThumbnail($path, "large");

    return $file->getContents();
  }

  // TODO: this method should be private
  /**
   * Adds a slash character to the beginning path.
   *
   * @param string $path File path
   *
   * @return string
   */
  public static function prependSlash($path)
  {
    return "/" . ltrim($path, "/");
  }

  /**
   * Gets the authentication helper.
   *
   * @return DropboxAuthHelper
   */
  private static function _getAuthHelper()
  {
    $dpApp = new DropboxApp(DROPBOX_APP_KEY, DROPBOX_APP_SECRET);
    $dp = new Dropbox($dpApp);

    return $dp->getAuthHelper();
  }

  /**
   * Gets the user's account.
   *
   * @param DbUser $user User
   *
   * @return Dropbox User account
   */
  private static function _getUserAccount($user)
  {
    $dpApp = new DropboxApp(
      DROPBOX_APP_KEY, DROPBOX_APP_SECRET, $user->dropboxToken
    );

    return new Dropbox($dpApp);
  }
}
