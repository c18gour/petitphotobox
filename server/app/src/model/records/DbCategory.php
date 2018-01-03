<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\exceptions\DatabaseError;
use petitphotobox\model\records\DbPicture;
use petitphotobox\model\records\DbUser;
use soloproyectos\db\DbConnector;
use soloproyectos\text\Text;

class DbCategory extends DbRecord
{
  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "category", $id);
  }

  /**
   * Is this category a 'main category'?
   *
   * @return boolean
   */
  public function isMain()
  {
    return Text::isEmpty($this->get("parent_category_id"));
  }

  /**
   * Gets the user.
   *
   * @return DbUser
   */
  public function getUser()
  {
    return new DbUser($this->db, $this->get("user_id"));
  }

  /**
   * Sets the user.
   *
   * @param DbUser $user User
   *
   * @return void
   */
  public function setUser($user)
  {
    $this->set("user_id", $user->getId());
  }

  /**
   * Gets the parent category.
   *
   * @return DbCategory
   */
  public function getParent()
  {
    return new DbCategory($this->db, $this->get("parent_category_id"));
  }

  /**
   * Sets the parent category.
   *
   * @param DbCategory $parent Parent category
   *
   * @return void
   */
  public function setParent($parent)
  {
    $this->set("parent_category_id", $parent->getId());
  }

  /**
   * Gets the title.
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->get("title");
  }

  /**
   * Sets the title.
   *
   * @param string $value Title
   *
   * @return void
   */
  public function setTitle($value)
  {
    $this->set("title", $value);
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
        return new DbCategory($this->db, $row["id"]);
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
        return new DbCategoryPicture($this->db, $row["id"]);
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
          "label" => $category->getTitle(),
          "items" => $category->getTree()
        ];
      },
      $this->getCategories()
    );
  }

  public function delete()
  {
    // deletes 'category pictures'
    $rows = $this->getCategoryPictures();
    foreach ($rows as $row) {
      $row->delete();
    }

    // delete subcategories
    $rows = $this->getCategories();
    foreach ($rows as $row) {
      $row->delete();
    }

    parent::delete();
  }
}
