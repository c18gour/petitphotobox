<?php
namespace petitphotobox\model\records;
use petitphotobox\core\model\record\DbRecord;

class DbSnapshot extends DbRecord
{
  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, "snapshot", $id);
  }

  /**
   * Gets the image path.
   *
   * @return string
   */
  public function getPath()
  {
    return $this->get("path");
  }

  /**
   * Sets the image path.
   *
   * @param string $value Image path
   *
   * @return void
   */
  public function setPath($value)
  {
    $this->set("path", $value);
  }
}
