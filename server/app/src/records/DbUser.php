<?php
namespace  petitphotobox\records;
use petitphotobox\core\dropbox\DropboxAccount;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbPicture;
use soloproyectos\db\DbConnector;
use soloproyectos\sys\file\SysFile;

class DbUser extends DbRecord
{
  public $name;
  public $language;
  public $dropboxId;
  public $dropboxToken;

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
   * Gets the Dropbox user's account.
   *
   * @return DropboxAccount
   */
  public function getAccount()
  {
    return new DropboxAccount($this->dropboxId, $this->dropboxToken);
  }

  /**
   * Gets the 'main category' of the current user.
   *
   * Each user has a 'main category'. This category has no parents and it's
   * unique for each user.
   *
   * @return DbCategory
   */
  public function getMainCategory()
  {
    $sql = "
    select
      id
    from category
    where parent_category_id is null
    and user_id = ?";
    $row = $this->db->query($sql, $this->getId());

    return new DbCategory($this->db, $this, $row["id"]);
  }

  /**
   * Gets the user's pictures.
   *
   * @return DbPicture[]
   */
  public function getPictures()
  {
    $sql = "
    select distinct
      p.id
    from picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    order by p.created_at desc";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPicture($this->db, $this, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Searches a user by the dropbox ID.
   *
   * @param DbConnector $db        Database connection
   * @param string      $dropboxId Dropbox ID
   *
   * @return DbUser
   */
  public static function searchByDropboxId($db, $dropboxId)
  {
    $ret = null;

    $sql = "
    select
      id
    from `user`
    where dropbox_id = ?";
    $row = $db->query($sql, $dropboxId);
    if (count($row) > 0) {
      $ret = new DbUser($db, $row["id"]);
    }

    return $ret;
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    DbTable::delete($this->db, "user", $this->id);
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
      $this->name,
      $this->language,
      $this->dropboxId,
      $this->dropboxToken
    ) = DbTable::select(
      $this->db,
      "user",
      ["id", "name", "language", "dropbox_id", "dropbox_token"],
      $this->id
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
      "user",
      [
        "name" => $this->name,
        "language" => $this->language,
        "dropbox_id" => $this->dropboxId,
        "dropbox_token" => $this->dropboxToken
      ],
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
    $userId = DbTable::insert(
      $this->db,
      "user",
      [
        "name" => $this->name,
        "language" => $this->language,
        "dropbox_id" => $this->dropboxId,
        "dropbox_token" => $this->dropboxToken
      ]
    );

    // creates the user's main category
    $categoryId = DbTable::insert(
      $this->db, "category", ["user_id" => $userId]
    );

    return $userId;
  }
}
