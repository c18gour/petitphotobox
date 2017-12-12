<?php
namespace petitphotobox\core\http\controller;
use soloproyectos\http\controller\HttpController;

abstract class BaseController extends HttpController {
  abstract public function getResponse();
}
