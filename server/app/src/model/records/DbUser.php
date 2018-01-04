<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use soloproyectos\db\DbConnector;
use petitphotobox\model\records\DbCategory;

class DbUser extends DbRecord
{
  /**
   * User name.
   * @var string
   */
  public $username;

  /**
   * Password.
   * @var string
   */
  public $password;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, $id);
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

    return new DbCategory($this->db, $this, $row["id"]);
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

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    DbTable::delete($this->db, "user", $this->id);
  }

  /**
   * {@inheritdoc}
   *
   * @return string Record ID
   */
  protected function select()
  {
    list(
      $id,
      $this->username,
      $this->password
    ) = DbTable::select(
      $this->db, "user", ["id", "username", "password"], $this->id
    );

    return $id;
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    DbTable::update(
      $this->db,
      "user",
      ["username" => $this->username, "password" => $this->password],
      $this->id
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    return DbTable::insert(
      $this->db,
      "user",
      ["username" => $this->username, "password" => $this->password]
    );
  }
}
