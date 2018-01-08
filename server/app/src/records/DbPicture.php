<?php
namespace  petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\exceptions\DatabaseError;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbSnapshot;

class DbPicture extends DbRecord
{
  private $_user;
  public $title;
  public $path;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db   Database connection
   * @param DbUser      $user Owner
   * @param string      $id   Record ID
   */
  public function __construct($db, $user, $id = null)
  {
    $this->_user = $user;
    parent::__construct($db, $id);
  }

  public function getCategories()
  {
    $sql = "
    select
      category_id
    from category_picture
    where picture_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategory($this->db, $this->_user, $row["category_id"]);
      },
      $rows
    );
  }

  /**
   * Gets the list of snapshots.
   *
   * @return DbSnapshot[]
   */
  public function getSnapshots()
  {
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord desc";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbSnapshot($this->db, $this->_user, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Gets the main snapshot.
   *
   * @return DbSnapshot
   */
  public function getMainSnapshot()
  {
    return array_shift($this->getSnapshots());
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    $sql = "
    delete p
    from picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where p.id = ?";
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
      p.id,
      p.title,
      s.path
    from picture as p
    inner join snapshot as s
      on s.picture_id = p.id
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where p.id = ?";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);
    $this->title = $row["title"];
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
    $snapshot = $this->getMainSnapshot();
    $snapshot->path = $this->path;
    $snapshot->save();

    $sql = "
    update picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    set
      p.title = ?
    where p.id = ?";
    $this->db->exec($sql, [$this->_user->getId(), $this->title, $this->id]);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    $pictureId = DbTable::insert(
      $this->db, "picture", ["title" => $this->title]
    );

    $snapshot = new DbSnapshot($this->db, $this->_user);
    $snapshot->pictureId = $pictureId;
    $snapshot->path = $this->path;
    $snapshot->save();

    return $pictureId;
  }
}
