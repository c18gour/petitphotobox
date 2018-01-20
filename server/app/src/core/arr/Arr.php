<?php
namespace petitphotobox\core\arr;

class Arr {
  /**
   * Gets the union of two list of items.
   *
   * @param mixed[]  $items1  List of items
   * @param mixed[]  $items2  List of items
   * @param callback $compare Compare function (not required)
   *
   * @return mixed[]
   */
  public static function union($items1, $items2, $compare = null)
  {
    return Arr::unique(array_merge($items1, $items2), $compare);
  }

  /**
   * Gets the intersection of two list of items.
   *
   * @param mixed[]  $items1  List of items
   * @param mixed[]  $items2  List of items1
   * @param callback $compare Compare function (not required)
   *
   * @return mixed[]
   */
  public static function intersect($items1, $items2, $compare = null)
  {
    return Arr::unique(
      array_values(
        array_filter(
          $items1,
          function ($item) use ($items2, $compare) {
            return !(Arr::search($item, $items2, $compare) < 0);
          }
        )
      ),
      $compare
    );
  }

  /**
   * Removes duplicate items. For example:
   *
   *   $items = [1, 2, 2, 4, 5, 5];
   *   $items2 = Arr::unique($items, function ($item1, $item2) {
   *     return $item1 == $item2;
   *   });
   *
   * @param mixed    $items   List of items
   * @param callback $compare Compare function (not required)
   *
   * @return mixed[]
   */
  public static function unique($items, $compare = null) {
    return array_values(
      array_filter(
        array_values($items),
        function ($item, $pos) use ($items, $compare) {
          return Arr::search($item, $items, $compare) >= $pos;
        },
        ARRAY_FILTER_USE_BOTH
      )
    );
  }

  /**
   * Searches an item position.
   *
   * This function returns -1 if the item was not found.
   *
   * @param mixed    $item    Item to search
   * @param mixed[]  $items   List of items
   * @param callback $compare Compare function (not required)
   *
   * @return int
   */
  public static function search($item, $items, $compare = null)
  {
    $ret = -1;

    foreach ($items as $i => $item2) {
      $isFound = $compare == null
        ? $item == $item2
        : call_user_func($compare, $item, $item2);

      if ($isFound) {
        $ret = $i;
        break;
      }
    }

    return $ret;
  }
}
