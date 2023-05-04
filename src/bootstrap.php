<?php
namespace App;

define("APP_DIR", $_SERVER['DOCUMENT_ROOT']);
define("VIEW_DIR", APP_DIR . "/view/");
define("SRC_DIR", APP_DIR . "/src/");
require APP_DIR . '/helpers.php';
require_once APP_DIR . '/vendor/autoload.php';

$router = new Router();
$application = new Application($router);
$application->run();
