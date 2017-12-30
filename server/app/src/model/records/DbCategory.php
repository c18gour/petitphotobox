<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\model\records\DbPicture;

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
    order by id";
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
    where category_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPicture($this->db, $row["picture_id"]);
      },
      $rows
    );
  }

  public function getTree($selectedId = null)
  {
    return array_map(
      function ($category) use ($selectedId) {
        $items = $category->getTree($selectedId);

        // an item is 'open' if it is 'selected' or any of its childs is 'open'
        $isSelected =  ($category->getId() === $selectedId);
        $isOpen = $isSelected;
        if (!$isOpen) {
          $selectedItems = array_filter(
            $items,
            function ($item) {
              return $item["open"];
            }
          );
          $isOpen = count($selectedItems) > 0;
        }

        return [
          "value" => $category->getId(),
          "label" => $category->getTitle(),
          "open" => $isOpen,
          "selected" => $isSelected,
          "items" => $items
        ];
      },
      $this->getCategories()
    );
  }
}
