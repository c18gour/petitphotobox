<?php
namespace petitphotobox\core\cache;

class CacheSystem
{
  public static function ifNotCached($createdAt, $etag, $callback) {
    // sends to the client information regarding to the 'cacheable object'
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $createdAt)." GMT");
    header("Etag: $etag");

    // tells the client to use the cached version
    if (
      @strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"]) == $createdAt ||
      @trim($_SERVER["HTTP_IF_NONE_MATCH"]) == $etag
    ) {
      header("HTTP/1.1 304 Not Modified");
      return;
    }

    call_user_func($callback);
  }
}
