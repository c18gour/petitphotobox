<?php
// TODO: rename this controller by picture-delete
header("Content-Type: application/json; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "app-config.php";
require_once "config.php";
use petitphotobox\controllers\CategoryPictureDeleteController;

$c = new CategoryPictureDeleteController();
$c->processRequest();
