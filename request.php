<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', __DIR__);
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once __DIR__ . '/vendor/autoload.php';

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");
Yaf_Registry::set('config', $application->getConfig());
$view = new Twig_Adapter(APPLICATION_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());
$application->getDispatcher()->setView($view);
AppLocal::setLocal(null);

$request=new Yaf_Request_Simple();
$application->getDispatcher()->dispatch($request);
var_dump($request);
?>
