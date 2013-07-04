<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', __DIR__.'/..');
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once ROOT_DIR . '/vendor/autoload.php';
require_once ROOT_DIR.'/static/Sta.php';

error_reporting(E_ALL ^ E_NOTICE);

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");

$application->bootstrap()->run();
?>
