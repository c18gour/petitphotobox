<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class CategoryDocument extends BaseDocument
{
  private $_record;
  private $_parent;
  private $_mainCategory;

  /**
   * Creates an instance.
   *
   * @param DbUser     $user     Current user
   * @param DbCategory $category Current category
   */
  public function __construct($record, $mainCategory, $parent = null)
  {
    $this->_record = $record;
    $this->_mainCategory = $mainCategory;
    $this->_parent = $parent;

    if ($this->_parent == null) {
      $this->_parent = $this->_record->getParent();
    }
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    return [
      "id" => $this->_record->getId(),
      "title" => $this->_record->getTitle(),
      "parentCategoryId" => $this->_parent->getId(),
      "categories" => $this->_mainCategory->getTree()
    ];
  }
}
