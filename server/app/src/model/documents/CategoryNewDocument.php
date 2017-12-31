<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class CategoryNewDocument extends BaseDocument
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
  public function __construct($record, $parent, $mainCategory)
  {
    $this->_record = $record;
    $this->_parent = $parent;
    $this->_mainCategory = $mainCategory;
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
