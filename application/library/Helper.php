<?php

class Helper{
    public static format( $str , $arr ){
        preg_replace('/#{([^\}]+)}/e', "helper_format_fn(\"\\1\", '" . $arr . "')", subject)
    }
}

function helper_format_fn( $match , $arr ){
    if( empty( $arr[ $match ] ) )
        return "";
    return $arr[ $match ];
}