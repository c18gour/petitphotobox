<?php
namespace petitphotobox\core\model\record;
use soloproyectos\db\Db;
use soloproyectos\db\DbConnector;
use soloproyectos\db\record\DbRecordTable;

/**
 * Implements the 'active record' approach.
 */
class DbRecord
{
  protected $db;
  private $_id;
  private $_tableName;
  // TODO:  change protected to private
  protected $columns;
  private $_isRecordFetched = false;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db        Database connection
   * @param string      $tableName Table namespace
   * @param string      $id        Record ID (optional)
   */
  public function __construct($db, $tableName, $id = null)
  {
    $this->db = $db;
    $this->_tableName = $tableName;
    $this->id = $id;

    $this->_fetchColumns();
    if ($id !== null) {
      $this->_fetchRecord();
    }
  }

  /**
   * Gets record ID.
   *
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  protected function get($colName)
  {
    return $this->columns[$colName]->getValue();
  }

  protected function set($colName, $value)
  {
    $this->columns[$colName]->setValue($value);
  }

  /**
   * Inserts or updates a column.
   *
   * For example:
   *
   *     // updates a record (ID = 1)
   *     $r = new DbRecord($db, "my-table", 1);
   *     $r["title"] = "A title";
   *     $r->save();
   *
   *     // inserts a record
   *     $r = new DbRecord($db, "my-table");
   *     $r["title"] = "A title";
   *     $r->save();
   *
   * @return void
   */
  public function save()
  {
    $r = new DbRecordTable($this->db, $this->_tableName);

    // gets the modified columns
    $columns = [];
    foreach ($this->columns as $colName => $column) {
      if ($column->hasChanged()) {
        $columns[$colName] = $column->getValue();
      }
    }

    $this->id = $r->save($columns, $this->id);
    $this->_fetchRecord();
  }

  /**
   * Deletes a record.
   *
   * @param DbConnector $db        Database connection
   * @param string      $tableName Table name
   * @param string      $id        Record ID
   *
   * @return void
   */
  static public function delete($db, $tableName, $id)
  {
    $r = new DbRecordTable($db, $tableName);
    $r->delete($id);
  }

  /**
   * Fetches columns from the table.
   *
   * @return void
   */
  private function _fetchColumns()
  {
    $rows = $this->db->query(
      "show columns from " . Db::quoteId($this->_tableName)
    );

    foreach ($rows as $row) {
      $name = $row["Field"];
      $this->columns[$name] = new DbRecordColumn();
    }
  }

  /**
   * Fetches the current record from the table.
   *
   * @return void
   */
  private function _fetchRecord()
  {
    $colNames = array_keys($this->columns);

    // fetches column values
    $r = new DbRecordTable($this->db, $this->_tableName);
    $cols = $r->select($colNames, $this->id);
    foreach ($colNames as $i => $colName) {
      $this->columns[$colName]->setInitialValue($cols[$i]);
    }
  }
}

/**
 * Represents a table column.
 *
 * This is an 'auxiliar' class for DbRecord.
 */
class DbRecordColumn
{
  private $_value;
  private $_hasChanged = false;

  /**
   * Has the column changed?
   *
   * @return boolean
   */
  public function hasChanged()
  {
    return $this->_hasChanged;
  }

  /**
   * Gets the column value.
   *
   * @return mixed
   */
  public function getValue()
  {
    return $this->_value;
  }

  /**
   * Sets a column value.
   *
   * @param mixed $value Column value
   *
   * @return void
   */
  public function setValue($value)
  {
    $this->_value = $value;
    $this->_hasChanged = true;
  }

  /**
   * Sets an initial column value.
   *
   * When setting an 'initial' column value, the column does not change.
   *
   * @param mixed $value Column value
   *
   * @return void
   */
  public function setInitialValue($value)
  {
    $this->_value = $value;
    $this->_hasChanged = false;
  }
}
