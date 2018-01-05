<?php
// TODO: rename this controller by picture-new
header("Content-Type: application/json; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "app-config.php";
require_once "config.php";
use petitphotobox\controllers\PictureNewController;

$c = new PictureNewController();
$c->processRequest();
