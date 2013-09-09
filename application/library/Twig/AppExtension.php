<?php

// TODO.. use auto load
class Twig_AppExtension extends Twig_Extension{
    public function getFunctions(){
        return array(

            // render sta elements
            new Twig_SimpleFunction(
                'sta',
                function( $arg ){
                    return Sta::render( array() , $arg );
                },
                array("is_safe" => array("html"))
            ),
            new Twig_SimpleFunction(
                'var',
                function( $arg ){
                    // get constants vars
                    return Constants::$$arg;
                }
            ),
            new Twig_SimpleFunction('isAuthed', function( $user ){
                return $user["auth_status"] == 3;
            }),
            // get language setting, and render right language and arguments
            // for example:
            // {{ _e("hello #[name] , you are #[age]" , {"name":user.name,"age":user.age}) }}
            new Twig_SimpleFunction('_e', "AppLocal::getString" , array("is_safe" => array("html")) ),
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
                                ( isset( $_POST[$key] ) ? $_POST[$key] :
                                ( isset( $_COOKIE[$key] ) ? $_COOKIE[$key] : null ) );
                }
            }),
            // require sta resource for current template
            // if you want to pass server parametrs to js , use second argument
            new Twig_SimpleFunction('require' , 'Sta::addPageSta'),
            new Twig_SimpleFunction('render', function(){
                return Sta::renderPageJs() . Sta::renderPageCss();
            } , array("needs_context"=> true , "is_safe" => array("html"))),
            // render_pagejs
            new Twig_SimpleFunction('renderPageJs', function( $env , $context ){
                return Sta::renderPageJs( $env->isDebug() ? null : $context["TEMPLATE"] );
            }, array("needs_context"=> true , "needs_environment" => true , "is_safe" => array("html"))),
            //'Sta::renderPageCss'
            new Twig_SimpleFunction('renderPageCss' , 'Sta::renderPageCss' , array("needs_context"=> true , "is_safe" => array("html")) )

        );
    }

    public function getFilters(){
        return array(
            // get sat files
            new Twig_SimpleFilter('url', 'Sta::url' ),
            new Twig_SimpleFilter('get_img_height' , function($url){
                preg_match("/_(\d+)-(\d+)(_(\d+)-(\d+))?\.\w+$/" , $url , $match );
                if( $match ){
                    if( $match[3] ){
                        return $match[2] / $match[1] * $match[4];
                    }
                    return $match[2];
                }
                return 0;
            }),
            new Twig_SimpleFilter('php_*', function ( $name ) {
                $args = func_get_args();
                array_shift( $args );
                return call_user_func_array( $name , $args );
            }),
            new Twig_SimpleFilter('build_page_url' , function( $page ){
                $uri = $_SERVER["REQUEST_URI"];
                if( preg_match("/([?&]page)=(\d+)/" , $uri ) ){
                    return preg_replace("/([?&]page)=(\d+)/", '\1=' . $page , $uri );
                } else {
                    if( strpos( $uri , "?" ) !== false ){
                        return $uri . '&page=' . $page;
                    } else {
                        return $uri . '?page=' . $page;
                    }
                }
            }),
            new Twig_SimpleFilter('number_format', function ( $num ) {
                return str_replace( ".00" , "" , number_format( $num , 2 , '.' , ',' ) );
            }),
            new Twig_SimpleFilter('cut_str', function ( $str , $num , $end_str = '...' ) {
                if( strlen($str) && mb_strlen( $str , 'utf-8') > $num ){
                    return mb_substr( $str, 0 , $num , 'utf-8' ) . $end_str;
                }
                return $str;
            }),

            new Twig_SimpleFilter('eval_str' , function( $str , $data ){
                if( strpos($str, '$') !== false ){
                    eval( "echo " . $str . ";");
                } else {
                    return $data[ $str ];
                }
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
