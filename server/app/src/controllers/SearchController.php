<?php
namespace petitphotobox\controllers;
use petitphotobox\core\arr\Arr;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbUser;
use soloproyectos\text\Text;

class SearchController extends AuthController
{
  private $_validDate = '/^\d{4}-\d{2}-\d{2}$/';
  private $_page;
  private $_categories;
  private $_pictures = [];

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addGetRequestHandler([$this, "onGetRequest"]);
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
        "page" => $this->_page,
        "numPages" => $this->_getNumPages(),
        "pictures" => array_map(
          function ($picture) {
            $snapshot = $picture->getMainSnapshot();
            $snapshots = $picture->getSnapshots();
            $categories = $picture->getCategories();

            return [
              "id" => $picture->getId(),
              "categories" => count($categories),
              "snapshots" => count($snapshots),
              "path" => $snapshot->path
            ];
          },
          $pictures
        ),
        "categoryIds" => array_map(
          function ($row) {
            return $row->getId();
          },
          $this->_categories
        ),
        "categories" => $mainCategory->getTree()
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
    $categoryIds = array_filter(explode(",", $this->getParam("categoryIds")));
    $this->_page = intval($this->getParam("page"));

    $this->_categories = array_map(
      function ($id) {
        return new DbCategory($this->db, $this->user, $id);
      },
      $categoryIds
    );

    foreach ($this->_categories as $i => $category) {
      if (!$category->isFound()) {
        throw new AppError("Category not found");
      }
    }
  }

  /**
   * Processes GET requests.
   *
   * @return void
   */
  public function onGetRequest()
  {
    $type = $this->getParam("type", "any");
    $recurse = filter_var($this->getParam("recurse"), FILTER_VALIDATE_BOOLEAN);
    $fromDate = $this->getParam("fromDate");
    $toDate = $this->getParam("toDate");

    if (array_search($type, ["any", "all"]) === false) {
      throw new ClientException("Invalid type");
    }

    if (!Text::isEmpty($fromDate) && !preg_match($this->_validDate, $fromDate)
        || !Text::isEmpty($toDate) && !preg_match($this->_validDate, $toDate)
    ) {
      throw new ClientException("Invalid date");
    }

    // filters by categories
    $this->_pictures = [];
    $method = $type == "any" ? "union" : "intersect";
    foreach ($this->_categories as $i => $category) {
      $pictures = $recurse
        ? $this->_getAllPictures($category)
        : $category->getPictures();

      if ($i > 0) {
        $this->_pictures = call_user_func(
          "petitphotobox\core\arr\Arr::$method",
          $this->_pictures,
          $pictures,
          function ($picture1, $picture2) {
            return $picture1->getId() == $picture2->getId();
          }
        );
      } else {
        $this->_pictures = $pictures;
      }
    }

    // filters by date
    if (!Text::isEmpty($fromDate)) {
      $date = strtotime($fromDate);

      $this->_pictures = array_filter(
        $this->_pictures,
        function ($picture) use ($date) {
          return $date <= strtotime($picture->getCreatedAt());
        }
      );
    }

    // filters by date
    if (!Text::isEmpty($toDate)) {
      $date = strtotime($toDate);

      $this->_pictures = array_filter(
        $this->_pictures,
        function ($picture) use ($date) {
          return strtotime($picture->getCreatedAt()) <= $date;
        }
      );
    }

    // sorts by date in descending order
    usort(
      $this->_pictures,
      function ($picture1, $picture2) {
        $date1 = strtotime($picture1->getCreatedAt());
        $date2 = strtotime($picture2->getCreatedAt());

        return $date2 - $date1;
      }
    );

    $this->_page = min(max(0, $this->_page), $this->_getNumPages() - 1);
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
   * Gets all pictures, including all those subcategory pictures.
   *
   * @param DbCategory $category Category
   *
   * @return DbPicture[]
   */
  private function _getAllPictures($category)
  {
    $ret = $category->getPictures();

    $items = $category->getCategories();
    foreach ($items as $item) {
      $ret = array_merge($ret, $this->_getAllPictures($item));
    }

    return Arr::unique(
      $ret,
      function ($item1, $item2) {
        return $item1->getId() == $item2->getId();
      }
    );
  }
}
