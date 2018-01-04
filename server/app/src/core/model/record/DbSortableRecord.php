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
  public $ord;

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
    return array_shift($this->getSortedRecords());
  }

  /**
   * Gets the last record.
   *
   * @return DbSortableRecord
   */
  public function getLastRecord()
  {
    return array_pop($this->getSortedRecords());
  }

  /**
   * Gets the previous record.
   *
   * @return DbSortableRecord
   */
  public function getPrevRecord()
  {
    $records = $this->getSortedRecords();

    return array_pop(
      array_filter(
        $records,
        function ($record) {
          return $record->ord < $this->get("ord");
        }
      )
    );
  }

  /**
   * Gets the next record.
   *
   * @return DbSortableRecord
   */
  public function getNextRecord()
  {
    $records = $this->getSortedRecords();

    return array_shift(
      array_filter(
        $records,
        function ($record) {
          return $record->ord > $this->get("ord");
        }
      )
    );
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
}
