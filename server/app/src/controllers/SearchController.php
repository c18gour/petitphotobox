<?php
namespace petitphotobox\controllers;
use petitphotobox\core\controller\AuthController;
use petitphotobox\core\exception\AppError;
use petitphotobox\core\exception\ClientException;
use petitphotobox\core\model\Document;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbUser;
use soloproyectos\text\Text;

class SearchController extends AuthController
{
  private $_categories;
  private $_pictures = [];

  /**
   * Creates a new instance..
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
        "pictures" => array_map(
          function ($picture) {
            return ["id" => $picture->getId(), "path" => $picture->path];
          },
          $this->_pictures
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
  public function onPostRequest()
  {
    $this->_pictures = $this->user->getPictures();
    foreach ($this->_categories as $i => $category) {
      if ($i > 0) {
        $this->_pictures = array_values(
          array_uintersect(
            $this->_pictures,
            $category->getPictures(),
            function ($row1, $row2) {
              return strnatcmp($row1->getId(), $row2->getId());
            }
          )
        );
      } else {
        $this->_pictures = $category->getPictures();
      }
    }
  }
}
