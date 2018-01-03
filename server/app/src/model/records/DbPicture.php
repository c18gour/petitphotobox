<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\model\records\DbCategory;
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

  /**
   * Gets picture's categories.
   *
   * @return DbCategory[]
   */
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
        return new DbCategory($this->db, $row["category_id"]);
      },
      $rows
    );
  }

  /**
   * Is this picture in a category?
   *
   * @param DbCategory $category Category
   *
   * @return boolean
   */
  public function isInCategory($category)
  {
    return count(
      array_filter(
        $category->getPictures(),
        function ($picture) {
          return $picture->getId() == $this->getId();
        }
      )
    ) > 0;
  }

  /**
   * {@inheritdoc}
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID
   *
   * @return void
   */
  public static function delete($db, $id)
  {
    parent::delete($db, "picture", $id);
  }
}
