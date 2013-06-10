<?php

// TODO.. use auto load
require_once __DIR__ . '/../../../static/Sta.php';
require_once __DIR__ . '/../AppLocal.php';

class Twig_AppExtension extends Twig_Extension{
    public function getFunctions(){
        return array(

            // render sta elements
            new Twig_SimpleFunction(
                'sta',
                function( $arg ){
                    return Sta::render(array() , $arg );
                },
                array("is_safe" => array("html"))
            ),

            // get language setting, and render right language and arguments
            // for example:
            // {{ _e("hello #[name] , you are #[age]" , {"name":user.name,"age":user.age}) }}
            new Twig_SimpleFunction('_e', "AppLocal::getString" ),

            // require sta resource for current template
            // if you want to pass server parametrs to js , use second argument
            new Twig_SimpleFunction('require' , 'Sta::addPageSta'),
            // render_pagejs
            new Twig_SimpleFunction('renderPageJs', 'Sta::renderPageJs' , array("needs_context"=> true , "is_safe" => array("html"))),
            //'Sta::renderPageCss'
            new Twig_SimpleFunction('renderPageCss' , 'Sta::renderPageCss' , array("needs_context"=> true , "is_safe" => array("html")) )

        );
    }

    public function getFilters(){
        return array(
            // get sat files
            new Twig_SimpleFilter('url', 'Sta::url' ),
        );
    }

    public function getName(){
        return "App Extension";
    }
}