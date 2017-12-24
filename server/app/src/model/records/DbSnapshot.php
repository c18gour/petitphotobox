<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;

class DbSnapshot extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "snapshot", $id);
  }

  public function getPath()
  {
    return $this->get("path");
  }

  public function setPath($value)
  {
    $this->set("path", $value);
  }
}
