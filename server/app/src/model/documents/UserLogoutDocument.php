<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

// TODO: remove this empty document (already exists one empty document)
class UserLogoutDocument extends BaseDocument
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
