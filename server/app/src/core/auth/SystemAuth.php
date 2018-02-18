<?php
namespace petitphotobox\core\auth;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

/**
 * Provides some methods to access the Dropbox user account.
 */
class SystemAuth
{
  public static function getUrl()
  {
    $helper = SystemAuth::_getAuthHelper();

    return $helper->getAuthUrl(CLIENT_REDIRECT_URL);
  }

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
