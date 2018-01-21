<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbUser;
use soloproyectos\text\Text;

class HomeController extends AuthController
{
  private $_page;
  private $_pictures;
  private $_category;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();
    $pictures = array_slice(
      $this->_pictures,
      MAX_ITEMS_PER_PAGE * $this->_page,
      MAX_ITEMS_PER_PAGE
    );

    return new Document(
      [
        "id" => $this->_category->getId(),
        "title" => $this->_category->title,
        "main" => $this->_category->getId() == $mainCategory->getId(),
        "page" => $this->_page,
        "numPages" => $this->_getNumPages(),
        "pictures" => array_map(
          function ($row) {
            $snapshot = $row->getMainSnapshot();
            $snapshots = $row->getSnapshots();
            $categories = $row->getCategories();

            return [
              "id" => $row->getId(),
              "categories" => count($categories),
              "snapshots" => count($snapshots),
              "path" => $snapshot->path
            ];
          },
          $pictures
        ),
        "categories" => $this->_getCategoryTree($mainCategory)
      ]
    );
  }

  /**
   * Processes OPEN requests.
   *
   * @return void
   */
  public function onOpenRequest()
  {
    $pictureId = $this->getParam("pictureId");
    $this->_page = intval($this->getParam("page"));

    $categoryId = $this->getParam("categoryId");
    $this->_category = Text::isEmpty($categoryId)
      ? $this->user->getMainCategory()
      : new DbCategory($this->db, $this->user, $categoryId);

    if (!$this->_category->isFound()) {
      throw new ClientException("Category not found");
    }

    $this->_pictures = $this->_category->getPictures();

    if (!Text::isEmpty($pictureId)) {
      $pos = $this->_searchPictureById($pictureId);
      if ($pos < 0) {
        throw new ClientException("Picture not found");
      }

      $this->_page = floor($pos / MAX_ITEMS_PER_PAGE);
    }

    $this->_page = min(max(0, $this->_page), $this->_getNumPages() - 1);
  }

  /**
   * Searches a pictue by ID.
   *
   * @param string $pictureId Picture ID
   *
   * @return int
   */
  private function _searchPictureById($pictureId)
  {
    $pos = -1;

    foreach ($this->_pictures as $i => $picture) {
      if ($picture->getId() == $pictureId) {
        $pos = $i;
        break;
      }
    }

    return $pos;
  }

  /**
   * Gets the number of pages.
   *
   * @return int
   */
  private function _getNumPages()
  {
    $numItems = count($this->_pictures);
    $numPages = floor($numItems / MAX_ITEMS_PER_PAGE);

    if (MAX_ITEMS_PER_PAGE * $numPages < $numItems) {
      $numPages++;
    }

    return $numPages;
  }

  /**
   * Gets the categories tree.
   *
   * @param DbCategory $category Category
   *
   * @return array An associative array
   */
  private function _getCategoryTree($category)
  {
    return array_map(
      function ($category) {
        $items = $this->_getCategoryTree($category);

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
          "title" => $category->title,
          "open" => $isOpen,
          "selected" => $isSelected,
          "items" => $items
        ];
      },
      $category->getCategories()
    );
  }
}
