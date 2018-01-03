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
   * Gets the list of pictures.
   *
   * @return DbPicture[]
   */
  public function getPictures()
  {
    $sql = "
    select
      picture_id
    from category_picture
    where category_id = ?
    order by ord";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPicture($this->db, $row["picture_id"]);
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

  /**
   * Moves a picture 'up'.
   *
   * @param DbPicture $picture A picture
   *
   * @return void
   */
  public function movePictureUp($picture)
  {
    $r = $this->_searchCategoryPicture($picture);
    if ($r == null) {
      throw new DatabaseError("Picture not found");
    }

    $prev = $r->getPrevRecord();
    if ($prev != null) {
      $r->swap($prev);
    }
  }

  /**
   * Moves a picture 'down'.
   *
   * @param DbPicture $picture A picture
   *
   * @return void
   */
  public function movePictureDown($picture)
  {
    $r = $this->_searchCategoryPicture($picture);
    if ($r == null) {
      throw new DatabaseError("Picture not found");
    }

    $next = $r->getNextRecord();
    if ($next != null) {
      $r->swap($next);
    }
  }

  /**
   * Removes a picture from this category.
   *
   * @param DbPicture $picture A picture
   *
   * @return void
   */
  public function deletePicture($picture)
  {
    $r = $this->_searchCategoryPicture($picture);
    if ($r == null) {
      throw new DatabaseError("Picture not found");
    }

    DbCategoryPicture::delete($this->db, $r->getId());

    // removes the picture if it doesn't belong to any other category
    if (count($picture->getCategories()) == 0) {
      DbPicture::delete($this->db, $picture->getId());
    }
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
    parent::delete($db, "category", $id);
  }

  /**
   * Searches a 'category picture' by picture.
   *
   * @param DbPicture $picture A picture
   *
   * @return DbCategoryPicture
   */
  private function _searchCategoryPicture($picture)
  {
    $ret = null;

    $sql = "
    select
      id
    from category_picture
    where category_id = ?
    and picture_id = ?";
    $row = $this->db->query($sql, [$this->getId(), $picture->getId()]);
    if (count($row) > 0) {
      $ret = new DbCategoryPicture($this->db, $row["id"]);
    }

    return $ret;
  }
}
