<?php
namespace petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;

class DbSnapshot extends DbRecord
{
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "snapshot", $id);
  }
}
