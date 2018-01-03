<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbSortableRecord;
use petitphotobox\model\records\DbPicture;
use petitphotobox\model\records\DbCategory;
use soloproyectos\db\Db;

class DbCategoryPicture extends DbSortableRecord
{
  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "category_picture", $id);
  }

  /**
   * Gets category.
   *
   * @return DbCategory
   */
  public function getCategory()
  {
    return new DbCategory($this->db, $this->get("category_id"));
  }

  /**
   * Gets picture.
   *
   * @return DbPicture
   */
  public function getPicture()
  {
    return new DbPicture($this->db, $this->get("picture_id"));
  }

  /**
   * {@inheritdoc}
   *
   * @return DbCategoryPicture[]
   */
  protected function getSortedRecords()
  {
    $sql = "
    select
      id
    from category_picture
    where category_id = ?
    order by ord";
    $rows = iterator_to_array(
      $this->db->query($sql, $this->get("category_id"))
    );

    return array_map(
      function ($row) {
        return new DbCategoryPicture($this->db, $row["id"]);
      },
      $rows
    );
  }
}
