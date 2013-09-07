<?php
/*
 * use request_uri specify url
 */
define('ROOT_DIR', __DIR__);
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once ROOT_DIR.'/static/Sta.php';
require_once __DIR__ . '/vendor/autoload.php';

if(file_exists('request.state')){
    $state = unserialize(file_get_contents('request.state'));
    $_COOKIE = array_merge($state['cookie'],$_COOKIE);
    session_id($state['session_id']);
    session_start();
    $_SESSION = $state['session'];
}

$application = new Yaf_Application( ROOT_DIR . "/conf/application.ini");
Yaf_Registry::set('config', $application->getConfig());
$view = new Twig_Adapter(APPLICATION_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());
$view->getEngine()->addExtension(new Twig_AppExtension());
$application->getDispatcher()->setView($view);
AppLocal::init(null);

//
$request=new Yaf_Request_Simple();
foreach($argv as $arg){
    if(preg_match('/(\w+)=(\w+)/', $arg, $matchedRequest)){
        $param = $matchedRequest[1];
        $value = $matchedRequest[2];
        if(property_exists('Yaf_Request_Simple',$param)){
            $request->$param=$value;
        }
    }
}
$response=$application->getDispatcher()->dispatch($request);
$state['cookie']=$_COOKIE;
$state['session']=$_SESSION;
$state['session_id'] = session_id();
file_put_contents('request.state',serialize($state));
//var_dump($response);
?>
