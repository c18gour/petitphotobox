<?php
// TODO: rename by user-register
header("Content-Type: application/json; charset=utf-8");
require_once "src/vendor/autoload.php";
require_once "app-config.php";
require_once "config.php";
use petitphotobox\controllers\SinginController;

$c = new SinginController();
$c->apply();
