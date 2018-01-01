<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class EmptyDocument extends BaseDocument
{
  /**
   * {@inheritdoc}
   *
   * @return object
   */
  protected function getJsonObject()
  {
    return [];
  }
}
