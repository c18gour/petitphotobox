<?php
namespace  petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use soloproyectos\db\DbConnector;

class DbTag extends DbRecord
{
  public $name;

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
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    DbTable::delete($this->db, "tag", $this->id);
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
      $this->name
    ) = DbTable::select($this->db, "tag", ["id", "name"], $this->id);

    return $id;
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    DbTable::update($this->db, "tag", ["name" => $this->name], $this->id);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    return DbTable::insert($this->db, "tag", ["name" => $this->name]);
  }

  /**
   * Searches a tag by its name.
   *
   * @param DbConnector $db   Database connection
   * @param string      $name Tag name
   *
   * @return DbTag
   */
  public static function searchByName($db, $name)
  {
    $ret = null;

    $sql = "
    select
      id
    from tag
    where name = ?";
    $row = $db->query($sql, $name);
    if (count($row) > 0) {
      $ret = new DbTag($db, $row["id"]);
    }

    return $ret;
  }
}
