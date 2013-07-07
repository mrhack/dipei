<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:58
 */

class AppException extends \Exception
{
    protected  $context;

    public function __construct($code=Constants::CODE_UNKNOWN,$msg='',&$context=array(),$previous=null){
        if(empty($msg)){
            $msg = _e(GenErrorDesc::$descs[$code]);
        }
        if(empty($msg)){
            $msg = _e(GenErrorDesc::$descs[Constants::CODE_UNKNOWN]);
        }
        $this->context=$context;
        parent::__construct($msg, $code, $previous);
    }

    public function getContext()
    {
        return $this->context;
    }
}