<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\model\records\DbSnapshot;

class DbPicture extends DbRecord
{
  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "picture", $id);
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
        return new DbSnapshot($this->db, $row["id"]);
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
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord desc
    limit 1";
    $row = $this->db->query($sql, $this->getId());

    return new DbSnapshot($this->db, $row["id"]);
  }
}
