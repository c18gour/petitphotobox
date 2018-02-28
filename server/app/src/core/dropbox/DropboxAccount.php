<?php
namespace petitphotobox\core\dropbox;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use petitphotobox\core\dropbox\DropboxService;

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
