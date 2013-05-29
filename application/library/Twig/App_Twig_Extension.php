<?php

require_once __DIR__ . '/../../../static/Sta.php';

class App_Twig_Extension extends Twig_Extension{
    public function getFunctions(){
        return array(
            new Twig_SimpleFunction('sta', 'app_render_sta', array("is_safe" => array("html"))),
        );
    }

    public function getName(){
        return "App Extension";
    }
}

function app_render_sta( $arg ){
    return Sta::init(array(
        "debug" => true,
        ) , $arg );
}