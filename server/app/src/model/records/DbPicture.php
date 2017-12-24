<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\model\records\DbSnapshot;

class DbPicture extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "picture", $id);
  }

  public function getSnapshots()
  {
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord desc";
    $rows = iterator_to_array($this->db->query($sql, $this->id));

    return array_map(
      function ($row) {
        return new DbSnapshot($this->db, $row["id"]);
      },
      $rows
    );
  }

  public function getMainSnapshot()
  {
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord desc
    limit 1";
    $row = $this->db->query($sql, $this->id);

    return new DbSnapshot($this->db, $row["id"]);
  }
}
