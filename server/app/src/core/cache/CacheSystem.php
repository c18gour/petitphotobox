<?php
namespace petitphotobox\core\cache;

class CacheSystem
{
  /**
   * Performs an action if the item has not been cached.
   *
   * @param string   $createdAt Creation date
   * @param string   $etag      ETag
   * @param callback $callback  Callback function
   *
   * @return void
   */
  public static function ifNotCached($createdAt, $etag, $callback)
  {
    // sends to the client information regarding to the 'cacheable object'
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $createdAt)." GMT");
    header("Etag: $etag");

    // tells the client to use the cached version
    $notModified = @strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) == $createdAt;
    $notMatch = @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag;
    if ($notModified || $notMatch) {
      header("HTTP/1.1 304 Not Modified");
      return;
    }

    call_user_func($callback);
  }
}
