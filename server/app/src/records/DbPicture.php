<?php
namespace  petitphotobox\records;
use petitphotobox\core\model\record\DbRecord;
use petitphotobox\core\model\record\DbTable;
use petitphotobox\exceptions\DatabaseError;
use petitphotobox\records\DbCategory;
use petitphotobox\records\DbCategoryPicture;
use petitphotobox\records\DbPictureTag;
use petitphotobox\records\DbSnapshot;
use petitphotobox\records\DbTag;

class DbPicture extends DbRecord
{
  private $_user;
  private $_createdAt;
  public $title;
  public $tags = [];
  public $paths = [];

  /**
   * Creates a new instance.
   *
   * @param DbConnector $db   Database connection
   * @param DbUser      $user Owner
   * @param string      $id   Record ID
   */
  public function __construct($db, $user, $id = null)
  {
    $this->_user = $user;
    parent::__construct($db, $id);
  }

  /**
   * Gets creation date.
   *
   * @return string
   */
  public function getCreatedAt()
  {
    return $this->_createdAt;
  }

  /**
   * Gets the picture's categories.
   *
   * @return DbCategory[]
   */
  public function getCategories()
  {
    $sql = "
    select
      category_id
    from category_picture
    where picture_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbCategory($this->db, $this->_user, $row["category_id"]);
      },
      $rows
    );
  }

  /**
   * Gets the list of snapshots.
   *
   * @return DbSnapshot[]
   */
  public function getSnapshots()
  {
    $sql = "
    select
      id
    from snapshot
    where picture_id = ?
    order by ord desc";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbSnapshot($this->db, $this->_user, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Gets the picture's tags.
   *
   * @return DbPictureTag[]
   */
  public function getPictureTags()
  {
    $sql = "
    select
      pt.id
    from picture_tag as pt
    inner join tag as t
      on t.id = pt.tag_id
    where pt.picture_id = ?";
    $rows = iterator_to_array($this->db->query($sql, $this->getId()));

    return array_map(
      function ($row) {
        return new DbPictureTag($this->db, $row["id"]);
      },
      $rows
    );
  }

  /**
   * Gets the main snapshot.
   *
   * @return DbSnapshot
   */
  public function getMainSnapshot()
  {
    return array_shift($this->getSnapshots());
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  public function delete()
  {
    $sql = "
    delete p, cp
    from picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where p.id = ?";
    $this->db->exec($sql, [$this->_user->getId(), $this->id]);
  }

  /**
   * {@inheritdoc}
   *
   * @return string Record ID
   */
  protected function select()
  {
    $sql = "
    select
      p.id,
      p.created_at,
      p.title,
      group_concat(distinct t.name) as tags,
      group_concat(distinct s.path order by s.ord desc) as paths
    from picture as p
    left join picture_tag as pt
      on pt.picture_id = p.id
    left join tag as t
      on t.id = pt.tag_id
    left join snapshot as s
      on s.picture_id = p.id
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    where p.id = ?
    group by p.id";
    $row = $this->db->query($sql, [$this->_user->getId(), $this->id]);
    $this->_createdAt = $row["created_at"];
    $this->title = $row["title"];
    $this->tags = array_filter(explode(",", $row["tags"]));
    $this->paths = array_filter(explode(",", $row["paths"]));
    return $row["id"];
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function update()
  {
    if (count($this->paths) < 1) {
      throw new DatabaseError("A picture must have at least one snapshot");
    }

    // removes all tags
    $tags = $this->getPictureTags();
    foreach ($tags as $t) {
      $t->delete();
    }

    // adds tags
    foreach ($this->tags as $name) {
      $tag = DbTag::searchByName($this->db, $name);
      if ($tag == null) {
        $tag = new DbTag($this->db);
        $tag->name = $name;
        $tag->save();
      }

      $pt = new DbPictureTag($this->db);
      $pt->tagId = $tag->getId();
      $pt->pictureId = $this->getId();
      $pt->save();
    }

    // removes all snapshots
    $snapshots = $this->getSnapshots();
    foreach ($snapshots as $s) {
      $s->delete();
    }

    // adds snapshots
    foreach ($this->paths as $path) {
      $s = new DbSnapshot($this->db, $this->_user);
      $s->pictureId = $this->id;
      $s->path = $path;
      $s->save();
    }

    $sql = "
    update picture as p
    inner join category_picture as cp
      on cp.picture_id = p.id
    inner join category as c
      on c.user_id = ?
      and c.id = cp.category_id
    set
      p.title = ?
    where p.id = ?";
    $this->db->exec($sql, [$this->_user->getId(), $this->title, $this->id]);
  }

  /**
   * {@inheritdoc}
   *
   * @return void
   */
  protected function insert()
  {
    if (count($this->paths) < 1) {
      throw new DatabaseError("A picture must have at least one snapshot");
    }

    // creates a picture
    $pictureId = DbTable::insert(
      $this->db, "picture", ["title" => $this->title]
    );

    // appends it to the main category
    $mainCategory = $this->_user->getMainCategory();
    $cp = new DbCategoryPicture($this->db, $this->_user);
    $cp->categoryId = $mainCategory->getId();
    $cp->pictureId = $pictureId;
    $cp->save();

    // adds tags
    foreach ($this->tags as $name) {
      $tag = DbTag::searchByName($this->db, $name);
      if ($tag == null) {
        $tag = new DbTag($this->db);
        $tag->name = $name;
        $tag->save();
      }

      $pt = new DbPictureTag($this->db);
      $pt->tagId = $tag->getId();
      $pt->pictureId = $pictureId;
      $pt->save();
    }

    // adds snapshots
    foreach ($this->paths as $path) {
      $s = new DbSnapshot($this->db, $this->_user);
      $s->pictureId = $pictureId;
      $s->path = $path;
      $s->save();
    }

    return $pictureId;
  }
}
