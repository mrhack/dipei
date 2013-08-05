<?php

/**
 * @name Bootstrap
 * @author wangfeng
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
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
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
        $detailRewrite=new Yaf_Route_Rewrite('detail/:uid',array(
            'controller'=>'Detail',
            'action'=>'index'
        ));
        $countryRewrite = new Yaf_Route_Rewrite('loc/country/:lid',array(
            'controller'=>'Loc',
            'action'=>'country'
        ));
        $cityRewrite = new Yaf_Route_Rewrite('loc/city/:lid',array(
            'controller'=>'Loc',
            'action'=>'city'
        ));
        $logoutRewrite = new Yaf_Route_Rewrite('logout', array(
            'controller'=>'Login',
            'action'=>'logout'
        ));
        $imgRewrite = new Yaf_Route_Regex('#^/img/(\d+/\d+_\d+-\d+)_(\d+)-(\d+)\.(png|jpg|gif)$#',
            array(
                'controller'=>'Image',
                'action'=>'thumb'
            ),
            array(
                1=>'basePath',
                2=>'sWidth',
                3=>'sHeight',
                4=>'suffix'
            )
        );
        $profileRewrite = new Yaf_Route_Rewrite('/profile/:type/:module',array(
            'controller'=>'Profile',
            'action'=>'index'
        ));
        $locationRewrite=new Yaf_Route_Rewrite('loc/:lid',array(
            'controller'=>'Loc',
            'action'=>'index'
        ) );
        $notFoundRewrite = new Yaf_Route_Rewrite('/404',array(
            'controller'=>'error',
            'action'=>'notfound'
        ));
        $postDetailRewrite=new Yaf_Route_Rewrite('/post/:type/:id',array(
            'controller'=>'Post',
            'action'=>'index'
        ));
        $dispatcher->getRouter()->addRoute('postDetailRewrite', $postDetailRewrite);
        $dispatcher->getRouter()->addRoute('notFoundRewrite', $notFoundRewrite);
        $dispatcher->getRouter()->addRoute('locRewrite',$locationRewrite);
        $dispatcher->getRouter()->addRoute('logoutRewrite', $logoutRewrite);
        $dispatcher->getRouter()->addRoute('countryRewrite', $countryRewrite);
        $dispatcher->getRouter()->addRoute('cityRewrite', $cityRewrite);
        $dispatcher->getRouter()->addRoute('profileRewrite', $profileRewrite);
        $dispatcher->getRouter()->addRoute('detailRewrite',$detailRewrite);
        $dispatcher->getRouter()->addRoute('imageRewrite', $imgRewrite);
    }

    public function _initView(Yaf_Dispatcher $dispatcher){
        //在这里注册自己的view控制器，例如smarty,firekylin
        $view = new Twig_Adapter(APPLICATION_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());
        $view->getEngine()->addExtension(new Twig_AppExtension());
        $dispatcher->setView($view);
    }
}
