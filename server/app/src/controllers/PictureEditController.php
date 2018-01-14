<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
use soloproyectos\text\Text;

class PictureEditController extends AuthController
{
  private $_picture;

  /**
   * Creates a new instance..
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();

    return new Document(
      [
        "id" => $this->_picture->getId(),
        "title" => $this->_picture->title,
        "tags" => implode(", ", $this->_picture->tags),
        "snapshots" => $this->_picture->paths,
        "categoryIds" => array_map(
          function ($row) {
            return $row->getId();
          },
          $this->_picture->getCategories()
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
    $pictureId = $this->getParam("pictureId");

    if (Text::isEmpty($pictureId)) {
      throw new AppError("Picture ID is required");
    }

    $this->_picture = new DbPicture($this->db, $this->user, $pictureId);
    if (!$this->_picture->isFound()) {
      throw new AppError("Picture not found");
    }
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $categoryIds = array_filter(explode(",", $this->getParam("categoryIds")));
    $title = $this->getParam("title");
    $tags = array_filter(
      array_map(
        "strtolower", array_map("trim", explode(",", $this->getParam("tags")))
      )
    );
    $snapshots = array_filter(
      array_map("trim", explode(",", $this->getParam("snapshots")))
    );

    if (count($snapshots) < 1) {
      throw new ClientException("Add one or more snapshots");
    }

    $categories = array_map(
      function ($id) {
        return new DbCategory($this->db, $this->user, $id);
      },
      $categoryIds
    );

    if (count($categories) < 1) {
      $categories = [$this->user->getMainCategory()];
    }

    foreach ($categories as $category) {
      if (!$category->isFound()) {
        throw new ClientException("Category not found");
      }
    }

    // updates the picture
    $this->_picture->title = $title;
    $this->_picture->tags = $tags;
    $this->_picture->paths = $snapshots;
    $this->_picture->save();

    // add the picture to the 'new' categories
    $categories1 = $this->_subtract(
      $categories,
      $this->_picture->getCategories()
    );

    foreach ($categories1 as $category) {
      $category->addPicture($this->_picture);
    }

    // removes the picture from the 'old' categories
    $categories2 = $this->_subtract(
      $this->_picture->getCategories(),
      $categories
    );

    foreach ($categories2 as $category) {
      $category->removePicture($this->_picture);
    }
  }

  /**
   * Gets the list of items that are in $items1 but not in $items2.
   *
   * @param DbRecord[] $items1 A list of items
   * @param DbRecord[] $items2 A list of items
   *
   * @return [type] Returns the $items1 - $items2
   */
  private function _subtract($items1, $items2)
  {
    return array_filter(
      $items1,
      function ($row1) use ($items2) {
        return count(
          array_filter(
            $items2,
            function ($row2) use ($row1) {
              return $row1->getId() == $row2->getId();
            }
          )
        ) == 0;
      }
    );
  }
}
