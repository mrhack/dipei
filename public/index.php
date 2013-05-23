<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', realpath(dirname(__FILE__).'/../'));
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once dirname(__FILE__) . '/../vendor/autoload.php';

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");

$application->bootstrap()->run();
?>
