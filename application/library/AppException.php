<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:58
 */

class AppException extends \Exception
{
    public function __construct($code=Constants::CODE_UNKNOWN,$msg='',$previous=null){
        if(empty($msg)){
            $msg = _e(GenErrorDesc::$descs[$code]);
        }
        if(empty($msg)){
            $msg = _e(GenErrorDesc::$descs[Constants::CODE_UNKNOWN]);
        }
        parent::__construct($msg, $code, $previous);
    }
}