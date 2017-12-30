<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class CategoryEditDocument extends BaseDocument
{
  private $_record;
  private $_user;

  /**
   * Creates an instance.
   *
   * @param DbUser     $user     Current user
   * @param DbCategory $category Current category
   */
  public function __construct($record, $user)
  {
    $this->_record = $record;
    $this->_user = $user;
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    $mainCategory = $this->_user->getMainCategory();
    $parent = $this->_record->getParent();

    return [
      "title" => $this->_record->getTitle(),
      "categories" => $mainCategory->getTree($parent->getId())
    ];
  }
}
