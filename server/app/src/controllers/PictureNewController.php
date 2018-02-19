<?php
namespace petitphotobox\controllers;
use petitphotobox\core\arr\Arr;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
use petitphotobox\records\DbSnapshot;
use soloproyectos\text\Text;

class PictureNewController extends AuthController
{
  private $_categories;
  private $_picture;

  /**
   * Creates a new instance.
   */
  public function __construct()
  {
    parent::__construct();
    $this->addOpenRequestHandler([$this, "onOpenRequest"]);
    $this->addPostRequestHandler([$this, "onPostRequest"]);
  }

  /**
   * {@inheritdoc}
   *
   * @return Document
   */
  public function getDocument()
  {
    $mainCategory = $this->user->getMainCategory();

    return new Document(
      [
        "id" => $this->_picture->getId(),
        "title" => $this->_picture->title,
        "tags" => implode(", ", $this->_picture->tags),
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

    $this->_categories = array_map(
      function ($id) {
        return new DbCategory($this->db, $this->user, $id);
      },
      $categoryIds
    );

    foreach ($this->_categories as $category) {
      if (!$category->isFound()) {
        throw new AppError("Category not found");
      }
    }

    $this->_picture = new DbPicture($this->db, $this->user);
  }

  /**
   * Processes POST requests.
   *
   * @return void
   */
  public function onPostRequest()
  {
    $title = $this->getParam("title");
    $tags = Arr::unique(
      array_filter(
        array_map(
          "strtolower",
          array_map("trim", explode(",", $this->getParam("tags")))
        )
      )
    );
    $snapshots = array_filter(
      array_map("trim", explode(",", $this->getParam("snapshots")))
    );

    if (count($snapshots) < 1) {
      throw new ClientException("Add one or more snapshots");
    }

    // creates a new picture
    $this->_picture->title = $title;
    $this->_picture->tags = $tags;
    $this->_picture->paths = $snapshots;
    $this->_picture->save();

    // adds it to the categories
    $isAddedToMainCategory = false;
    $mainCategory = $this->user->getMainCategory();
    foreach ($this->_categories as $category) {
      $isMainCategory = $category->getId() == $mainCategory->getId();
      $isAddedToMainCategory = $isAddedToMainCategory || $isMainCategory;

      if (!$isMainCategory) {
        $category->addPicture($this->_picture);
      }
    }

    // removes it from the main category
    if (count($this->_categories) > 0 && !$isAddedToMainCategory) {
      $mainCategory->removePicture($this->_picture);
    }
  }
}
