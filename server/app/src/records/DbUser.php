<?php
namespace  petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
use soloproyectos\db\DbConnector;

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
   * Gets the user's pictures.
   *
   * @return DbPicture[]
   */
  public function getPictures()
  {
    $sql = "
    select distinct
      p.id
    from picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    order by p.created_at desc";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPicture($this->db, $this, $row["id"]);
      },
      $rows
    );
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
