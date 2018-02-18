<?php
namespace petitphotobox\core\auth;
use Kunnu\Dropbox\Models\AccessToken;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

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

  private static function _getAuthHelper()
  {
    $dpApp = new DropboxApp(DROPBOX_APP_KEY, DROPBOX_APP_SECRET);
    $dp = new Dropbox($dpApp);

    return $dp->getAuthHelper();
  }
}
