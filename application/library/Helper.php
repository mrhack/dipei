<?php
/*
 * @date 2013-06-01
 * @author hdg1988@gmail.com
 * @desc Helper for lepei application
 */
class Helper{
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


    // get language of $str
    public static function lang( $str , $args = array() ){
        // TODO.. get lang from server or cookie
        $lang = 'en';
        // TODO.. get properties from file
        $props = array();

        if( isset($props[ $str ]) ){
            $str = $props[ $str ];
        }

        return self::format( $str , $args );
    }
}