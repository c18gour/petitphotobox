<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class CategoryEditDocument extends BaseDocument
{
  private $_record;
  private $_mainCategory;

  /**
   * Creates an instance.
   *
   * @param DbUser     $user     Current user
   * @param DbCategory $category Current category
   */
  public function __construct($record, $mainCategory)
  {
    $this->_record = $record;
    $this->_mainCategory = $mainCategory;
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    $parent = $this->_record->getParent();

    return [
      "id" => $this->_record->getId(),
      "title" => $this->_record->getTitle(),
      "parentCategoryId" => $parent->getId(),
      "categories" => $this->_mainCategory->getTree()
    ];
  }
}
