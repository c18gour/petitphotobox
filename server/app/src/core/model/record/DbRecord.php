<?php
namespace petitphotobox\core\model\record;
use soloproyectos\db\Db;
use soloproyectos\db\DbConnector;
use soloproyectos\text\Text;

abstract class DbRecord
{
  protected $db;
  protected $id;
  private $_isInsertMode;

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
    $this->_isInsertMode = Text::isEmpty($id);

    if (!Text::isEmpty($id)) {
      $this->refresh();
    }
  }

  /**
   * Gets the record ID.
   *
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Was the record found?
   *
   * @return boolean
   */
  public function isFound()
  {
    return !Text::isEmpty($this->id);
  }

  /**
   * Saves this record.
   *
   * @return void
   */
  public function save()
  {
    if ($this->_isInsertMode) {
      $this->id = $this->insert();
    } else {
      $this->update($this->id);
    }
  }

  /**
   * Refreshes this record.
   *
   * @return void
   */
  public function refresh()
  {
    $this->id = $this->select();
  }

  /**
   * Deletes a record from a table.
   *
   * @return void
   */
  abstract public function delete();

  /**
   * Selects a record from a table.
   *
   * @return string Record ID
   */
  abstract protected function select();

  /**
   * Updates a record from a table.
   *
   * @return void
   */
  abstract protected function update();

  /**
   * Inserts a record into a table.
   *
   * @return string Last inserted id
   */
  abstract protected function insert();
}
