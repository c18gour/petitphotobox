<?php
header("Content-Type: application/json; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "config.php";
require_once "app-config.php";
use petitphotobox\controllers\CategoryPictureDownController;

$c = new CategoryPictureDownController();
$c->processRequest();
