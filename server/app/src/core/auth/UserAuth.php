<?php
namespace petitphotobox\core\auth;
use petitphotobox\exceptions\AuthException;
use petitphotobox\records\DbUser;
use soloproyectos\db\DbConnector;
// TODO: remove DbRecordTable package
use soloproyectos\db\record\DbRecordTable;
use soloproyectos\http\data\HttpSession;

class UserAuth
{
  /**
   * Creates a user.
   *
   * @param DbConnector $db       Database connection
   * @param string      $username User name
   * @param string      $password Password
   *
   * @return DbUser
   */
  public static function create($db, $username, $password)
  {
    if (!preg_match('/^[a-z1-9_]+$/', $username)) {
      throw new AuthException(
        "The username must be written in lowercase " .
        "and can only contain the following characters: a..z, 1..10, _"
      );
    }

    if (strlen($password) < MIN_PASSWORD_LENGTH) {
      throw new AuthException(
        "Password must have at least " . MIN_PASSWORD_LENGTH . " characters"
      );
    }

    $user = new DbUser($db);
    $user->username = $username;
    $user->password = password_hash($password, PASSWORD_BCRYPT);
    $user->save();

    return $user;
  }

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
    $userId = HttpSession::get("user_id");

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
   * Logs into the system.
   *
   * @param DbConnector $db       Database connection
   * @param string      $username Username
   * @param string      $password Password
   *
   * @return DbUser
   */
  public static function login($db, $username, $password)
  {
    $user = DbUser::searchByName($db, $username);
    if ($user === null) {
        throw new AuthException(
          "The user was not found or the password is wrong..."
        );
    }

    // verifies the password
    if (!password_verify($password, $user->password)) {
      throw new AuthException(
        "The user was not found or the password is wrong"
      );
    }

    // registers the user in the system
    HttpSession::set("user_id", $user->getId());

    return $user;
  }

  /**
   * Logs out from the system.
   *
   * @return void
   */
  public static function logout()
  {
    HttpSession::delete("user_id");
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
