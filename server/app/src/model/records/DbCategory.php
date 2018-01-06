<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\model\records\DbUser;
use soloproyectos\db\DbConnector;
use soloproyectos\text\Text;

class DbCategory extends DbRecord
{
  private $_user;
  public $parentCategoryId;
  // TODO: ensure that it is a valid title
  public $title;

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

  public function getParent()
  {
    return new DbCategory($this->db, $this->_user, $this->parentCategoryId);
  }

  /**
   * Is this category a 'main category'?
   *
   * @return boolean
   */
  public function isMain()
  {
    return Text::isEmpty($this->parentCategoryId);
  }

  /**
   * Gets subcategories from the current category.
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
    order by title";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategory($this->db, $this->_user, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Gets the list of 'category pictures' sorted by 'ord'.
   *
   * @return DbCategoryPicture[]
   */
  public function getCategoryPictures()
  {
    $sql = "
    select
      id
    from category_picture
    where category_id = ?
    order by ord";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategoryPicture($this->db, $this->_user, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Gets the category tree.
   *
   * @return array Associative array
   */
  public function getTree()
  {
    return array_map(
      function ($category) {
        return [
          "value" => $category->getId(),
          "label" => $category->title,
          "items" => $category->getTree()
        ];
      },
      $this->getCategories()
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
    delete from category
    where user_id = ?
    and id = ?";
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
      id,
      parent_category_id,
      title
    from category
    where user_id = ?
    and id = ?";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);
    $this->parentCategoryId = $row["parent_category_id"];
    $this->title = $row["title"];

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
    update category set
      parent_category_id = ?,
      title = ?
    where user_id = ?
    and id = ?";
    $this->db->exec(
        $sql,
        [
          $this->parentCategoryId,
          $this->title,
          $this->_user->getId(),
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
      "category",
      [
        "user_id" => $this->_user->getId(),
        "parent_category_id" => $this->parentCategoryId,
        "title" => $this->title
      ]
    );
  }
}
