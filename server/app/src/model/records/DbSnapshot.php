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

  public function isMain()
  {
    $picture = $this->getPicture();
    $snapshot = $picture->getMainSnapshot();

    return $this->getId() == $snapshot->getId();
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

  public function getPicture()
  {
    return new DbPicture($this->db, $this->get("picture_id"));
  }
}
