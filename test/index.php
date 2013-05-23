<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', realpath(dirname(__FILE__).'/../'));
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once dirname(__FILE__) . '/../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem( APPLICATION_PATH.'/views' );
$twig = new Twig_Environment( $loader, array(
'cache'=>false,
'debug'=>true
    ));
//for debug
$twig->addExtension(new Twig_Extension_Debug());


$path = $_SERVER['REQUEST_URI'];
$path = substr($path, strlen('/test/'));

function extend($includeFile,$val)
{
    $parentVal=include_once $includeFile;
    return $val+$parentVal;
}

//var_dump(extend('global.php',array('user'=>array('name'=>'wangfeng'),'other'=>'ytx')));


$template=$twig->loadTemplate($path.'.twig');


$val = include_once 'global.php';
$val = extend(dirname(__FILE__).'/data/'.$path.'.php',$val);
var_dump($val);

echo $template->render($val);

?>
