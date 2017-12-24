<?php
namespace petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use soloproyectos\db\DbConnector;
use petitphotobox\records\DbCategory;

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

  /**
   * Gets the 'main category' of the current user.
   *
   * Every user has a 'main category'. This category has no parents and it's
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
   * @param string $username Username
   *
   * @return DbUser
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
      $ret = new DbUser($db, $row["id"]);
    }

    return $ret;
  }
}
