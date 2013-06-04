<?php

// TODO.. use auto load
require_once __DIR__ . '/../../../static/Sta.php';
require_once __DIR__ . '/../Helper.php';

class Twig_AppExtension extends Twig_Extension{
    public function getFunctions(){
        return array(

            // render sta elements
            new Twig_SimpleFunction(
                'sta',
                function( $arg ){
                    return Sta::init(array(
                        "debug" => false,
                        ) , $arg );
                },
                array("is_safe" => array("html"))
            ),

            // get language setting, and render right language and arguments
            // for example:
            // {{ lang("hello #[name] , you are #[age]" , {"name":user.name,"age":user.age}) }}
            new Twig_SimpleFunction('lang', "Helper::lang" ),
        );
    }

    public function getFilters(){
        return array(
            // get sat files
            new Twig_SimpleFilter('sta', function( $file ){
                // TODO.. get global server config
                $server = '';
                return $server . '/' . $file;
            }),
        );
    }

    public function getName(){
        return "App Extension";
    }
}
