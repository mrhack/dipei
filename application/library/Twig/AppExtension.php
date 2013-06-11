<?php

// TODO.. use auto load
require_once __DIR__ . '/../../../static/Sta.php';
require_once __DIR__ . '/../AppHelper.php';
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
            // get request parameters
            new Twig_SimpleFunction('request' , function( $key , $type = null){
                switch( $type ){
                    case "get":
                        return isset( $_GET[$key] ) ? $_GET[$key] : null;
                    case "post":
                        return isset( $_POST[$key] ) ? $_POST[$key] : null;
                    case "cookie":
                        return isset( $_COOKIE[$key] ) ? $_COOKIE[$key] : null;
                    default:
                        return isset( $_GET[$key] ) ? $_GET[$key] :
                                (isset( $_POST[$key] ) ? $_POST[$key] :
                                (isset( $_COOKIE[$key] ) ? $_COOKIE[$key] : null));
                }
            }),
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
            // get score desc
            new Twig_SimpleFilter('score_desc' , function( $score ){
                return $score;
            }),
        );
    }
    public function getOperators(){
        return array(
            array(
                '!' => array('precedence' => 50, 'class' => 'Twig_Node_Expression_Unary_Not'),
            ),
            array(
                '||' => array('precedence' => 10, 'class' => 'Twig_Node_Expression_Binary_Or', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
                '&&' => array('precedence' => 15, 'class' => 'Twig_Node_Expression_Binary_And', 'associativity' => Twig_ExpressionParser::OPERATOR_LEFT),
            ),
        );
    }

    public function getName(){
        return "App Extension";
    }
}