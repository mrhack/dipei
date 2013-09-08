<?php
//phpinfo();exit;
/*
 * entrance of dipei app
 */
define('ROOT_DIR', __DIR__.'/..');
define('APPLICATION_PATH',ROOT_DIR.'/application');
define('BACKEND_PATH',ROOT_DIR.'/backend');
define('IMAGE_SERVER_URL','www.lepei.cc/public/img');
require_once ROOT_DIR . '/vendor/autoload.php';
require_once ROOT_DIR.'/static/Sta.php';
require_once ROOT_DIR.'/application/library/extend.php';

error_reporting(E_ALL ^ E_NOTICE);
list(,$base)=explode('/',$_SERVER['REQUEST_URI']);

//www.lepei.cc/backend
if(strcasecmp('backend',$base) === 0){
    $application = new Yaf_Application( ROOT_DIR . "/conf/backend.ini");

    $application->bootstrap()->run();
}else{
    $application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");

    $application->bootstrap()->run();
}
?>
