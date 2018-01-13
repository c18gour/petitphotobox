<?php
namespace petitphotobox\controllers;
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
        "tags" => implode(
          ", ",
          array_map(
            function ($tag) {
              return $tag->name;
            },
            $this->_picture->getTags()
          )
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
    $tagNames = array_filter(
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

    // creates a new picture
    $this->_picture->title = $title;
    foreach ($snapshot as $path) {
      $this->_picture->addSnapshotPath($path);
    }
    $this->_picture->save();

    // adds tags
    foreach ($tagNames as $name) {
      $this->_picture->addTagName($name);
    }

    // ...and adds it to the category
    foreach ($this->_categories as $category) {
      $category->addPicture($this->_picture);
    }
  }
}
