<?php
namespace petitphotobox\core\auth;
use \Exception;
use soloproyectos\db\DbConnector;
use soloproyectos\http\data\HttpSession;

class User {
  /**
   * User's ID.
   *
   * @var string
   */
  private $_id;

  /**
   * Creates a user.
   *
   * @param  string $id User's ID
   */
  private function __constructor($id)
  {
    $this->_id = $id;
  }

  /**
   * Logs into the system.
   *
   * @param  string $username Username
   * @param  string $password Password
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
      throw new Exception("User not found");
    }

    // verifies the password
    if (!password_verify($password, $row["password"])) {
      throw new Exception("Invalid password");
    }

    // TODO: improve the security system (tokens)
    // registers the user in the system
    HttpSession::set("logged", true);

    return new User($row["id"]);
  }

  /**
   * Logs out from the system.
   *
   * @return void
   */
  public function logout()
  {
    HttpSession::delete("logged");
  }

  /**
   * Is the user logged?
   *
   * @return boolean
   */
  public function isLogged()
  {
    return HttpSession::exist("logged");
  }
}
