<?php
namespace  petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use soloproyectos\db\DbConnector;

class DbPictureTag extends DbRecord
{
  public $pictureId;
  public $tagId;

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db Database connection
   * @param string      $id Record ID (not required)
   */
  public function __construct($db, $id = null)
  {
    parent::__construct($db, $id);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    DbTable::delete($this->db, "picture_tag", $this->id);
  }

  /**
   * {@inheritdoc}
   *
   * @return string Record ID
   */
  protected function select()
  {
    list(
      $id,
      $this->pictureId,
      $this->tagId
    ) = DbTable::select(
      $this->db, "picture_tag", ["id", "picture_id", "tag_id"], $this->id
    );

    return $id;
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    DbTable::update(
      $this->db,
      "picture_tag",
      ["picture_id" => $this->pictureId, "tag_id", $this->tagId],
      $this->id
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    return DbTable::insert(
      $this->db,
      "picture_tag",
      ["picture_id" => $this->pictureId, "tag_id" => $this->tagId]
    );
  }
}
