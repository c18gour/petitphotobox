<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbSortableRecord;

class DbSnapshot extends DbSortableRecord
{
  private $_user;
  public $pictureId;
  public $path;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db   Database connection
   * @param DbUser      $user Owner
   * @param string      $id   Record ID (not required)
   */
  public function __construct($db, $user, $id = null)
  {
    $this->_user = $user;
    parent::__construct($db, $id);
  }

  /**
   * Is this snapshot main?
   *
   * @return boolean
   */
  public function isMain()
  {
    $picture = new DbPicture($this->db, $this->_user, $this->pictureId):
    $snapshot = $picture->getMainSnapshot();

    return $this->id == $snapshot->getId();
  }

  /**
   * {@inheritdoc}
   *
   * @return DbSnapshot[]
   */
  protected function getSortedRecords()
  {
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord";
    $rows = iterator_to_array(
      $this->db->query($sql, $this->pictureId)
    );

    return array_map(
      function ($row) {
        return new DbSnapshot($this->db, $row["id"]);
      },
      $rows
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    $sql = "
    delete s
    from snapshot as s
    inner join picture as p
      on p.id = s.picture_id
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where s.id = ?";
    $this->db->exec($sql, [$this->_user->getId(), $this->id]);
  }

  /**
   * {@inheritdoc}
   *
   * @return string Record ID
   */
  protected function select()
  {
    $sql = "
    select
      s.id,
      s.picture_id,
      s.path
    from snapshot as s
    inner join picture as p
      on p.id = s.picture_id
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where s.id = ?";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);
    $this->pictureId = $row["picture_id"];
    $this->path = $row["path"];

    return $row["id"];
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    $sql = "
    update snapshot as s
    inner join picture as p
      on p.id = s.picture_id
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.picture_id
    set
      s.picture_id = ?,
      s.path = ?,
      s.ord = ?
    where s.id = ?";
    $row = $this->db->exec(
      $sql,
      [
        $this->_user->getId(),
        $this->pictureId,
        $this->path,
        $this->ord,
        $this->id
      ]
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
      [
        "picture_id" => $this->pictureId,
        "path" => $this->path,
        "ord" => $htis->ord
      ]
    );
  }
}
