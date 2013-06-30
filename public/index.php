<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', realpath(dirname(__FILE__).'/../'));
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once ROOT_DIR.'/static/Sta.php';

error_reporting(E_ALL ^ E_NOTICE);

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");

$application->bootstrap()->run();
?>
