<?php
header("Content-Type: application/json; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "app-config.php";
require_once "config.php";
use petitphotobox\controllers\PictureDeleteController;

$c = new PictureDeleteController();
$c->processRequest();
