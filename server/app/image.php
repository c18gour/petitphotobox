<?php
header("Content-Type: image/jpeg");
require_once "src/vendor/autoload.php";
require_once "app-config.php";
require_once "config.php";
use petitphotobox\controllers\ImageController;

$c = new ImageController();
$c->processRequest();
