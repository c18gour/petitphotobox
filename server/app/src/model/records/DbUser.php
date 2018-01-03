<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use soloproyectos\db\DbConnector;
use petitphotobox\model\records\DbCategory;

class DbUser extends DbRecord
{
  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "user", $id);
  }

  /**
   * Gets the user name.
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->get("username");
  }

  /**
   * Sets the user name.
   *
   * @param string $value User name
   *
   * @return void
   */
  public function setUsername($value)
  {
    $this->set("username", $value);
  }

  /**
   * Gets the encrypted password.
   *
   * @return string
   */
  public function getPassword()
  {
    return $this->get("password");
  }

  /**
   * Sets the encrypted password.
   *
   * @param string $value Encrypted password
   *
   * @return void
   */
  public function setPassword($value)
  {
    $this->set("password", $value);
  }

  /**
   * Gets the 'main category' of the current user.
   *
   * Each user has a 'main category'. This category has no parents and it's
   * unique for each user.
   *
   * @return DbCategory
   */
  public function getMainCategory()
  {
    $sql = "
    select
      id
    from category
    where parent_category_id is null
    and user_id = ?";
    $row = $this->db->query($sql, $this->getId());

    return new DbCategory($this->db, $row["id"]);
  }

  public function delete()
  {
    $category = $this->getMainCategory();
    $category->delete();

    parent::delete();
  }

  /**
   * Searches an user by name.
   *
   * @param DbConnector $db       Database connection
   * @param string      $username Username
   *
   * @return DbUser
   */
  public static function searchByName($db, $username)
  {
    $ret = null;

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
}
