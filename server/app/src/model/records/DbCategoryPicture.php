<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbSortableRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\model\records\DbPicture;
use petitphotobox\model\records\DbCategory;
use soloproyectos\db\Db;

class DbCategoryPicture extends DbSortableRecord
{
  private $_user;
  public $categoryId;
  public $pictureId;

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
   * Gets picture.
   *
   * @return DbPicture
   */
  public function getPicture()
  {
    return new DbPicture($this->db, $this->_user, $this->pictureId);
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
    delete cp
    from category_picture as cp
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where cp.id = ?";
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
      cp.id,
      cp.category_id,
      cp.picture_id,
      cp.ord
    from category_picture as cp
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where cp.id = ?";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);
    $this->categoryId = $row["category_id"];
    $this->pictureId = $row["picture_id"];
    $this->ord = $row["ord"];

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
    update category_picture as cp
    inner join category as p
      on p.user_id = ?
      and p.id = cp.category_id
    set
      cp.category_id = ?,
      cp.picture_id = ?,
      cp.ord = ?
    where cp.id = ?";
    $row = $this->db->exec(
      $sql,
      [
        $this->_user->getId(),
        $this->categoryId,
        $this->pictureId,
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
      "category_picture",
      [
        "category_id" => $this->categoryId,
        "picture_id" => $this->pictureId,
        "ord" => $this->ord
      ]
    );
  }
}
