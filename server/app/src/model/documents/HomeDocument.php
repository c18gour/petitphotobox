<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\db\DbConnector;

class HomeDocument extends BaseDocument
{
  private $_user;
  private $_category;

  /**
   * Creates a new instance.
   *
   * @param DbUser     $user     User
   * @param DbCategory $category Category
   */
  public function __construct($user, $category)
  {
    $this->_user = $user;
    $this->_category = $category;

    $this->setProperty("categories", []);
    $this->setProperty("pictures", []);
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    $items = $this->_getCategoriesTree();

    return [
      "categories" => $items,
      "pictures" => $this->_getPictures()
    ];
  }

  /**
   * Gets the categories tree.
   *
   * @param DbCategory $category Category (not required)
   *
   * @return array An associative array
   */
  private function _getCategoriesTree($category = null)
  {
    if ($category === null) {
      $category = $this->_user->getMainCategory();
    }

    return array_map(
      function ($category) {
        $items = $this->_getCategoriesTree($category);

        // an item is 'open' if it is 'selected' or any of its childs is 'open'
        $isSelected =  ($category->getId() === $this->_category->getId());
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
          "id" => $category->getId(),
          "title" => $category->getTitle(),
          "open" => $isOpen,
          "selected" => $isSelected,
          "items" => $items
        ];
      },
      $category->getCategories()
    );
  }

  /**
   * Gets the list of pictures.
   *
   * @return array Associative array
   */
  private function _getPictures()
  {
    $category = $this->_category === null
      ? $this->user->getMainCategory()
      : $this->_category;

    return array_map(
      function ($picture) {
        $snapshot = $picture->getMainSnapshot();

        return ["id" => $picture->getId(), "path" => $snapshot->getPath()];
      },
      $category->getPictures()
    );
  }
}
