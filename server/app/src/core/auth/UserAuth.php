<?php
namespace petitphotobox\core\auth;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use petitphotobox\core\dropbox\DropboxService;
use petitphotobox\exceptions\SessionError;
use petitphotobox\records\DbUser;
use soloproyectos\db\DbConnector;
use soloproyectos\http\data\HttpCookie;

class UserAuth
{
  /**
   * Gets the current user instance.
   *
   * @param DbConnector $db Database connection
   *
   * @return DbUser
   */
  public static function getInstance($db)
  {
    $ret = null;
    $userId = HttpCookie::get("user_id");

    $sql = "
    select
      id
    from `user`
    where id = ?";
    $row = $db->query($sql, $userId);
    if (count($row) > 0) {
      $ret = new DbUser($db, $userId);
    }

    return $ret;
  }

  /**
   * Registers a user into the system.
   *
   * @param DbConnector $db    Database connection
   * @param string      $code  Dropbox code
   * @param string      $state Dropbox state
   *
   * @return DbUser
   */
  public static function login($db, $code, $state)
  {
    $dropboxId = "";
    $dtopboxToken = "";

    try {
      $token = DropboxService::getAccessToken($code, $state);
      $dropboxId = $token->getUid();
      $dtopboxToken = $token->getToken();
    } catch (DropboxClientException $e) {
      throw new SessionError($e->getMessage());
    }

    // searches or creates a new user
    $user = DbUser::searchByDropboxId($db, $dropboxId);
    if ($user === null) {
      $user = new DbUser($db);
    }

    $user->dropboxId = $dropboxId;
    $user->dropboxToken = $dtopboxToken;
    $user->save();

    // registers the user in the system
    HttpCookie::set("user_id", $user->getId());

    return $user;
  }

  /**
   * Logs out from the system.
   *
   * @return void
   */
  public static function logout()
  {
    HttpCookie::delete("user_id");
  }

  /**
   * Is the user logged?
   *
   * @param DbConnector $db Database connection
   *
   * @return boolean
   */
  public static function isLogged($db)
  {
    return UserAuth::getInstance($db) !== null;
  }
}
