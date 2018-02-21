<?php
namespace petitphotobox\core\auth;
use Kunnu\Dropbox\Models\AccessToken;
use petitphotobox\exceptions\AuthException;
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
   * Creates the user if it wasn't already registered.
   *
   * @param DbConnector $db          Database connection
   * @param AccessToken $accessToken Access token
   *
   * @return DbUser
   */
  public static function login($db, $accessToken)
  {
    // searches or creates a new user
    $user = DbUser::searchByAuthId($db, $accessToken->getUid());
    if ($user === null) {
        $user = new DbUser($db);
        $user->authId = $accessToken->getUid();
    }
    $user->authToken = $accessToken->getToken();
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
