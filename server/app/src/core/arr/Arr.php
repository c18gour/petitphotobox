<?php
namespace petitphotobox\core\arr;

class Arr {
  /**
   * Removes duplicate items.
   *
   * @param string[] $items List of items
   *
   * @return string[]
   */
  public static function removeDuplicateItems($items) {
    return array_values(
      array_filter(
        array_values($items),
        function ($item, $pos) use ($items) {
          return Arr::_searchItem($items, $item) >= $pos;
        },
        ARRAY_FILTER_USE_BOTH
      )
    );
  }

  /**
   * Searches an item.
   *
   * @param string[] $items List of items
   * @param string   $str   A string
   *
   * @return int
   */
  private static function _searchItem($items, $str) {
    $str = trim(strtolower($str));

    foreach ($items as $pos => $item) {
      if (trim(strtolower($item)) == $str) {
        return $pos;
      }
    }

    return -1;
  }
}
