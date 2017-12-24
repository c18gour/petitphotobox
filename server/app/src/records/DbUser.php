<?php
namespace petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\records\DbCategory;

class DbUser extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "user", $id);
  }

  /**
   * Gets the 'main category' of the current user.
   *
   * Every user has a 'main category'. This category has no parents and it's
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
    $row = $this->db->query($sql, $this->id);

    return new DbCategory($this->db, $row["id"]);
  }
}
