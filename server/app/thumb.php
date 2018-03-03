<?php
header("Content-Type: image/jpeg");
// header("Content-Type: text/plain; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "config.php";
require_once "app-config.php";
use petitphotobox\controllers\ThumbController;

$c = new ThumbController();
$c->processRequest();
