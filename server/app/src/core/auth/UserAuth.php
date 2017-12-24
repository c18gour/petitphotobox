<?php
namespace petitphotobox\core\auth;
use petitphotobox\exceptions\AuthException;
use petitphotobox\records\DbUser;
use soloproyectos\db\DbConnector;
use soloproyectos\db\record\DbRecordTable;
use soloproyectos\http\data\HttpSession;

class UserAuth
{
  /**
   * Creates a user.
   *
   * @param string $username [description]
   * @param string $password [description]
   *
   * @return DbUser
   */
  public static function create($username, $password)
  {
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

    $r = new DbRecordTable($db, "user");
    $userId = $r->save(
      [
        "username" => $username,
        "password" => password_hash($password, PASSWORD_BCRYPT)
      ]
    );

    return new DbUser($db, $userId);
  }

  /**
   * Searches an user by name.
   *
   * @param string $username Username
   *
   * @return DbUser
   */
  // TODO: this method belongs to the model (DbUser)
  public static function searchByName($username)
  {
    $ret = null;
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

    $sql = "
    select
      id
    from `user`
    where username = ?";
    $row = $db->query($sql, $username);
    if (count($row) > 0) {
      $ret = new DbUser($db, $row["id"]);
    }

    return $ret;
  }

  /**
   * Gets the current user instance.
   *
   * @return DbUser
   */
  public static function getInstance()
  {
    $ret = null;
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

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
   * @param string $username Username
   * @param string $password Password
   *
   * @return DbUser
   */
  public static function login($username, $password)
  {
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

    // searches a user by name
    $sql = "
    select
      id,
      password
    from `user`
    where username = ?";
    $row = $db->query($sql, $username);
    if (count($row) == 0) {
        throw new AuthException("User not found");
    }

    // verifies the password
    if (!password_verify($password, $row["password"])) {
      throw new AuthException("Invalid password");
    }

    // registers the user in the system
    HttpSession::set("user_id", $row["id"]);

    return new DbUser($db, $row["id"]);
  }

  /**
   * Logs out from the system.
   *
   * @return void
   */
  public function logout()
  {
    HttpSession::delete("user_id");
  }

  /**
   * Is the user logged?
   *
   * @return boolean
   */
  public function isLogged()
  {
    return UserAuth::getInstance() !== null;
  }
}
