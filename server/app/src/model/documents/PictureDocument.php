<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class PictureDocument extends BaseDocument
{
  private $_record;
  private $_mainCategory;
  private $_category;

  /**
   * Creates an instance.
   *
   * @param DbCategory $record       A category
   * @param DbCategory $mainCategory The main category
   * @param DbCategory $category     Category
   */
  public function __construct($record, $mainCategory, $category)
  {
    $this->_record = $record;
    $this->_mainCategory = $mainCategory;
    $this->_category = $category;
  }

  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    $picture = $this->_record->getPicture();

    return [
      "id" => $this->_record->getId(),
      "title" => $picture->title,
      "categoryId" => $this->_category->getId(),
      "categories" => $this->_mainCategory->getTree()
    ];
  }
}
