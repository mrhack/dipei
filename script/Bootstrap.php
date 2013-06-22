<?php
/**
 * User: wangfeng
 * Date: 13-6-15
 * Time: 上午11:56
 */
define('ROOT_DIR', __DIR__.'/..');
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once ROOT_DIR . '/vendor/autoload.php';
require_once ROOT_DIR.'/static/Sta.php';

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");
Yaf_Registry::set('config', $application->getConfig());

AppLocal::init('zh_CN');

function getLogger($path)
{
    static $logger=null;
    if(!empty($logger)) return $logger;
    $name=basename($path,'.php');
    $logger = AppLogger::newLogger($name,sprintf('%s.%s',Constants::PATH_LOG.'/'.$name,date('Ymd')));
    $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));
    return $logger;
}
