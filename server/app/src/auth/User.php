<?php
namespace petitphotobox\auth;
use \Exception;
use petitphotobox\exceptions\AuthException;
use petitphotobox\exceptions\SessionError;
use soloproyectos\db\DbConnector;
use soloproyectos\db\exception\DbException;
use soloproyectos\db\record\DbRecordTable;
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
   * Creates a user.
   *
   * @param string $username [description]
   * @param string $password [description]
   *
   * @return User
   */
  public static function create($username, $password)
  {
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

    // TODO: puede arrojar una excepción
    $r = new DbRecordTable($db, "user");
    $userId = $r->save(
      [
        "username" => $username,
        "password" => password_hash($password, PASSWORD_BCRYPT)
      ]
    );

    return new User($userId);
  }

  /**
   * Searches an user by name.
   *
   * @param string $username Username
   *
   * @return User
   */
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
      $ret = new User($row["id"]);
    }

    return $ret;
  }

  /**
   * Gets the current user instance.
   *
   * @return User
   */
  // TODO: no debería arrojar una excepción
  // TODO: cambiar por `getInstance`
  public static function retrieveInstance()
  {
    $db = new DbConnector(DBNAME, DBUSER, DBPASS, DBHOST);

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
