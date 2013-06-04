<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 下午10:56
 */
class AppLocal{

    private static $local = 'zh_cn';

    public static function setLocal($local){
        if(!empty($local)){
            self::$local=$local;
        }
    }

    public static function getString($propertyKey,$local=null)
    {
        //
        return '';
    }

    private static function checkLocal(){

    }
}

function _e($k,$local=null){
    return AppLocal::getString($k, $local);
}