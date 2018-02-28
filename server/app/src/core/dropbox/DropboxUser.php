<?php
namespace petitphotobox\core\dropbox;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use petitphotobox\core\dropbox\DropboxService;

class DropboxUser
{
  private $_token;

  public function __construct($token)
  {
    $this->_token = $token;
  }

  public static function createByCodes($code, $state)
  {
    $token = DropboxService::getAccessToken($code, $state);

    return new DropboxUser($token);
  }

  public function getName()
  {
    $account = $this->_getAccount();

    return $account->getDisplaName();
  }

  public function getEmail()
  {
    $account = $this->_getAccount();

    return $account->getEmail();
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
