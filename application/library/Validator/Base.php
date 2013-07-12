<?php
/**
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午4:09
 */
abstract class Validator_Base
{
    public $errorMsg;

    public function __construct($config)
    {
        if(is_array($config)){
            foreach($config as $k=>$v){
                $this->$k=$v;
            }
        }
        $this->checkParams();
    }

    public function checkParams()
    {

    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    abstract function validate($val,$field='',$ctx=null);
}