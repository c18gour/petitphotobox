<?php
namespace petitphotobox\core\http\controller;
use soloproyectos\http\controller\HttpController;

class BaseController extends HttpController {

  /**
   * Gets the HTTP response.
   *
   * @return array Associative array
   */
  public function getResponse()
  {
    return ["status" => null];
  }
}
