<?php
/*
 * @date 2013-06-01
 * @author hdg1988@gmail.com
 * @desc Helper for lepei application
 */
class AppHelper{
    // format string width given data
    // for example:
    // format("user name is #[name] ccc, and age is #[age]" , array("name"=>"hdg" , "age"=> 25))
    public static function format( $str , $args ){
        return preg_replace_callback( "|#\[([^\]]+)\]|" , function ( $match ) use ( $args ) {
            if( isset($args[$match[1]]) ){
                return $args[$match[1]];
            }
            return '';
        } , $str );
    }

    // get str length , ugly method
    public static function length( $str ){
        preg_match_all('/./us', $str, $m);
        return count( $m[0] );
    }
}