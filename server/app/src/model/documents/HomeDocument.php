<?php
namespace petitphotobox\model\documents;
use petitphotobox\core\model\document\BaseDocument;

class HomeDocument extends BaseDocument
{
  public function __construct()
  {
    $this->setProperty("categories", []);
    $this->setProperty("pictures", []);
  }

  public function getCategories()
  {
    return $this->getProperty("categories");
  }

  public function setCategories($value)
  {
    $this->setProperty("categories", $value);
  }

  public function getPictures()
  {
    return $this->getProperty("pictures");
  }

  public function setPictures($value)
  {
    $this->setProperty("pictures", $value);
  }
}
