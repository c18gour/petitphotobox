<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;
use petitphotobox\model\records\DbCategory;
use soloproyectos\db\DbConnector;

class HomeDocument extends BaseDocument
{
  private $_user;
  private $_category;

  public function __construct($user, $category)
  {
    $this->_user = $user;
    $this->_category = $category;

    $this->setProperty("categories", []);
    $this->setProperty("pictures", []);
  }

  protected function getJsonObject()
  {
    return [
      "categories" => $this->_getCategoriesTree(),
      "pictures" => $this->_getPictures()
    ];
  }

  private function _getCategoriesTree($category = null)
  {
    if ($category === null) {
      $category = $this->_user->getMainCategory();
    }

    return array_map(
      function ($category) {
        return [
          "id" => $category->getId(),
          "title" => $category->getTitle(),
          "selected" => ($category->getId() === $this->_category->getId()),
          "items" => $this->_getCategoriesTree($category),
        ];
      },
      $category->getCategories()
    );
  }

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
