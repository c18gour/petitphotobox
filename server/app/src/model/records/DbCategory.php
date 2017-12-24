<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\model\records\DbPicture;

class DbCategory extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "category", $id);
  }

  public function getTitle()
  {
    return $this->get("title");
  }

  public function setTitle($value)
  {
    $this->set("title", $value);
  }

  /**
   * Gets sub-categories from the current category.
   *
   * @return DbCategory[]
   */
  public function getCategories()
  {
    $sql = "
    select
      id
    from category
    where parent_category_id = ?
    order by id";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategory($this->db, $row["id"]);
      },
      $rows
    );
  }

  public function getPictures()
  {
    $sql = "
    select
      picture_id
    from category_picture
    where category_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPicture($this->db, $row["picture_id"]);
      },
      $rows
    );
  }
}
