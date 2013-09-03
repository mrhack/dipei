<?php
/**
 * User: wangfeng
 * Date: 13-9-3
 * Time: 下午9:03
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $arrConfig);
    }

    public function _initLocal(){
        // 初始化语言环境
        AppLocal::init();
    }

    public function _initDebug(){
        // 初始化开发环境  和 线上环境
        define( "IS_DEBUG" , true );
        //Sta::setDebug( IS_DEBUG );
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {

    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {

    }

    public function _initView(Yaf_Dispatcher $dispatcher){
        //在这里注册自己的view控制器，例如smarty,firekylin
        $view = new Twig_Adapter(BACKEND_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());
        $view->getEngine()->addExtension(new Twig_AppExtension());
        $dispatcher->setView($view);
    }
}
