<?php
namespace petitphotobox\core\http;

class HttpClient
{
  /**
   * Gets client's language.
   *
   * @return string
   */
  function getLanguage()
  {
    $lang = I18N_DEFAULT_LANG;

    if (array_key_exists("HTTP_ACCEPT_LANGUAGE", $_SERVER)) {
      $info1 = array_filter(explode(";", $_SERVER["HTTP_ACCEPT_LANGUAGE"]));
      if (count($info1) > 0) {
        $info2 = array_filter(explode(",", $info1[0]));

        if (count($info2) > 0) {
          $lang = $info2[1];
        }
      }
    }

    return $lang;
  }
}
