<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use soloproyectos\db\DbConnector;
use petitphotobox\model\records\DbCategory;

class DbUser extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "user", $id);
  }

  public function getId()
  {
    return $this->id;
  }

  public function getUsername()
  {
    // TODO: $this->get("username")
    return $this->columns["username"]->getValue();
  }

  public function setUsername($value)
  {
    // TODO: $this->set("username", $value)
    $this->columns["username"]->setValue($value);
  }

  public function getPassword()
  {
    return $this->columns["password"]->getValue();
  }

  public function setPassword($value)
  {
    $this->columns["password"]->setValue($value);
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
    $row = $this->db->query($sql, $this->id);

    return new DbCategory($this->db, $row["id"]);
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
