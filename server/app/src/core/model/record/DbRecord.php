<?php
namespace petitphotobox\core\model\record;
use soloproyectos\db\Db;
use soloproyectos\db\DbConnector;
use soloproyectos\db\record\DbRecordTable;
use soloproyectos\text\Text;

/**
 * Implements the 'active record' approach.
 */
class DbRecord
{
  protected $db;
  private $_tableName;
  private $_id;
  private $_columns;
  private $_isRecordFetched = false;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db         Database connection
   * @param string      $_tableName Table namespace
   * @param string      $id         Record ID (optional)
   */
  public function __construct($db, $_tableName, $id = null)
  {
    $this->db = $db;
    $this->_tableName = $_tableName;
    $this->_id = $id;

    $this->_fetchColumns();
    if ($id !== null) {
      $this->_fetchRecord();
    }
  }

  /**
   * Was the record found in the database?
   *
   * @return boolean [description]
   */
  public function isFound()
  {
    return !Text::isEmpty($this->getId());
  }

  /**
   * Gets record ID.
   *
   * @return string
   */
  public function getId()
  {
    return $this->_columns["id"]->getValue();
  }

  /**
   * Gets a column value.
   *
   * @param stirng $colName Column name
   *
   * @return mixed
   */
  protected function get($colName)
  {
    return $this->_columns[$colName]->getValue();
  }

  /**
   * Sets a column value.
   *
   * @param string $colName Column name
   * @param mixed  $value   Column value
   *
   * @return void
   */
  protected function set($colName, $value)
  {
    $this->_columns[$colName]->setValue($value);
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
    foreach ($this->_columns as $colName => $column) {
      if ($column->hasChanged()) {
        $columns[$colName] = $column->getValue();
      }
    }

    $this->_id = $r->save($columns, $this->_id);
    $this->_fetchRecord();
  }

  /**
   * Deletes this record.
   *
   * @return void
   */
  public function delete()
  {
    $r = new DbRecordTable($this->db, $this->_tableName);
    $r->delete($this->_id);

    $this->_fetchRecord();
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
      $this->_columns[$name] = new DbRecordColumn();
    }
  }

  /**
   * Fetches the current record from the table.
   *
   * @return void
   */
  private function _fetchRecord()
  {
    $colNames = array_keys($this->_columns);

    // fetches column values
    $r = new DbRecordTable($this->db, $this->_tableName);
    $cols = $r->select($colNames, $this->_id);
    foreach ($colNames as $i => $colName) {
      $this->_columns[$colName]->setInitialValue($cols[$i]);
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
