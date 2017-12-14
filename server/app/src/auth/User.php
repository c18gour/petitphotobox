<?php
namespace petitphotobox\auth;
use \Exception;
use petitphotobox\exceptions\AuthException;
use petitphotobox\exceptions\DbError;
use petitphotobox\exceptions\SessionError;
use soloproyectos\db\DbConnector;
use soloproyectos\db\exception\DbException;
use soloproyectos\http\data\HttpSession;

class User
{

  /**
   * User's ID.
   *
   * @var string
   */
  private $_id;

  /**
   * Creates a user.
   *
   * @param string $id User's ID
   */
  private function __construct($id)
  {
    $this->_id = $id;
  }

  /**
   * Gets the current user instance.
   *
   * @return User
   */
  public static function retrieveInstance()
  {
    $ret = null;

    try {
      $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
    } catch (DbException $e) {
      throw new DbError($e->getMessage(), $e);
    }

    $userId = HttpSession::get("user_id");
    $sql = "
    select
      id
    from `user`
    where id = ?";
    $row = $db->query($sql, $userId);
    if (count($row) < 1) {
      throw new SessionError("Your session has expired");
    }

    return new User($userId);
  }

  /**
   * Logs into the system.
   *
   * @param string $username Username
   * @param string $password Password
   *
   * @return User
   */
  public static function login($username, $password)
  {
    try {
      $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);
    } catch (DbException $e) {
      throw new DbError($e->getMessage(), $e);
    }

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

    return new User($row["id"]);
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
    return User::retrieveInstance() !== null;
  }
}
