<?php
namespace petitphotobox\core\model\record;
use petitphotobox\core\model\record\DbRecord;
use soloproyectos\db\Db;

abstract class DbSortableRecord extends DbRecord
{
  /**
   * Order.
   * @var int
   */
  protected $ord;

  /**
   * Gets the ord column.
   *
   * @return int
   */
  public function getOrd()
  {
    return $this->ord;
  }

  /**
   * Gets the list of records sorted by the 'ord' column in ascendent order
   *
   * @return DbSortableRecord[]
   */
  abstract protected function getSortedRecords();

  /**
   * Gets the first record.
   *
   * @return DbSortableRecord
   */
  public function getFirstRecord()
  {
    $records = $this->getSortedRecords();

    return array_shift($records);
  }

  /**
   * Gets the last record.
   *
   * @return DbSortableRecord
   */
  public function getLastRecord()
  {
    $records = $this->getSortedRecords();

    return array_pop($records);
  }

  /**
   * Gets the previous record.
   *
   * @return DbSortableRecord
   */
  public function getPrevRecord()
  {
    $records = array_filter(
      $this->getSortedRecords(),
      function ($record) {
        return $record->ord < $this->ord;
      }
    );

    return array_pop($records);
  }

  /**
   * Gets the next record.
   *
   * @return DbSortableRecord
   */
  public function getNextRecord()
  {
    $records = array_filter(
      $this->getSortedRecords(),
      function ($record) {
        return $record->ord > $this->ord;
      }
    );

    return array_shift($records);
  }

  /**
   * Swaps this record by the selected record.
   *
   * @param DbSortableRecord $selectedRecord Selected record
   *
   * @return void
   */
  public function swap($selectedRecord)
  {
    $records = $this->getSortedRecords();
    $len = count($records);

    if ($len > 0) {
      $lastRecord = $records[$len - 1];
      $maxOrd = $lastRecord->ord;

      // swaps current record by selected record
      $records = array_map(
        function ($record) use ($selectedRecord) {
          if ($record->getId() == $selectedRecord->getId()) {
            return $this;
          } elseif ($record->getId() == $this->getId()) {
            return $selectedRecord;
          }

          return $record;
        },
        $records
      );

      // sorts records
      for ($i = 0; $i < $len; $i++) {
        $record = $records[$i];
        $record->ord = $maxOrd + $i + 1;
        $record->save();
      }

      // re-sort records
      for ($i = 0; $i < $len; $i++) {
        $record = $records[$i];
        $record->ord = $record->ord - $maxOrd;
        $record->save();
      }
    }
  }

  /**
   * Gets next ord.
   *
   * @return int
   */
  protected function getNextOrd()
  {
    $ret = 0;

    $record = $this->getLastRecord();
    if ($record != null) {
      $ret = $record->ord + 1;
    }

    return $ret;
  }
}
