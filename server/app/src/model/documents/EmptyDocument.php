<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\Document;

class EmptyDocument extends Document
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
