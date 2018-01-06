<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\exceptions\DatabaseError;
use petitphotobox\model\records\DbCategory;
use petitphotobox\model\records\DbSnapshot;

class DbPicture extends DbRecord
{
  private $_user;
  // TODO: ensure it is a valid title
  public $title;

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

  // TODO: rmeove this?
  /**
   * Gets picture's categories.
   *
   * @return DbCategory[]
   */
  public function getCategoryPictures()
  {
    $sql = "
    select
      id
    from category_picture
    where picture_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategoryPicture($this->db, $this->_user, $row["id"]);
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
      p.title
    from picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where p.id = ?";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);

    return $row["id"];
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    throw new DatabaseError("Method not implemented");
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    return DbTable::insert($this->db, "picture", ["title" => $this->title]);
  }
}
