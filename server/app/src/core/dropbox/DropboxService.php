<?php
namespace petitphotobox\core\dropbox;
use Kunnu\Dropbox\Authentication\DropboxAuthHelper;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

class DropboxService
{
  /**
   * Gets the authentication url.
   *
   * @return string
   */
  public static function getAuthUrl()
  {
    $helper = DropboxService::_getHelper();

    return $helper->getAuthUrl(CLIENT_REDIRECT_URL);
  }

  /**
   * Gets the user's token.
   *
   * @param string $code  Code
   * @param string $state Description
   *
   * @return string[] [<Dropbox ID>, <Dropbox token>]
   */
  public static function getAccessToken($code, $state)
  {
    $helper = DropboxService::_getHelper();
    $token = $helper->getAccessToken($code, $state, CLIENT_REDIRECT_URL);

    return [$token->getUid(), $token->getToken()];
  }

  /**
   * Gets dropbox helper.
   *
   * @return DropboxAuthHelper
   */
  public static function _getHelper()
  {
    $app = new DropboxApp(DROPBOX_APP_KEY, DROPBOX_APP_SECRET);
    $box = new Dropbox($app);

    return $box->getAuthHelper();
  }
}
