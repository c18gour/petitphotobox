<?php
namespace petitphotobox\core\model\record;
use soloproyectos\db\Db;
use soloproyectos\db\DbConnector;
use soloproyectos\text\Text;

abstract class DbRecord
{
  protected $db;
  protected $id;

  /**
   * Constructor.
   *
   * @param DbConnector $db Databse connection
   * @param string      $id Record ID
   */
  public function __construct($db, $id = null)
  {
    $this->db = $db;
    $this->id = $id;

    if (!Text::isEmpty($id)) {
      $this->refresh();
    }
  }

  public function getId()
  {
    return $this->id;
  }

  public function isFound()
  {
    return !Text::isEmpty($this->id);
  }

  public function save()
  {
    if (Text::isEmpty($this->id)) {
      $this->id = $this->insert();
    } else {
      $this->update($this->id);
    }
  }

  public function refresh()
  {
    $this->id = $this->select();
  }

  /**
   * Deletes a record from a table.
   *
   * @var void
   */
  abstract public function delete();

  /**
   * Selects a record from a table.
   *
   * @var string Record ID
   */
  abstract protected function select();

  /**
   * Updates a record from a table.
   *
   * @var void
   */
  abstract protected function update();

  /**
   * Inserts a record into a table.
   *
   * @var string Last inserted id
   */
  abstract protected function insert();
}
