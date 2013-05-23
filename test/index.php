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

// 1. get template name
$tpl = $_GET['tpl'];
// 2. get

//$twig->display( $tpl ,  )
var_dump( $_GET );
// 1.TODO .. read from test arguments

// 2.TODO .. use yaf to render Twig template
// $tpl = $_GET['tpl'];
?>
