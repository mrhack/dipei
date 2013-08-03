<?php
/**
 * User: wangfeng
 * Date: 13-7-29
 * Time: 下午8:30
 */
class Validator_NickEmail extends Validator_Base
{
    public $escape;

    public function __construct($errorMsg,$escape=null){
        $this->errorMsg=$errorMsg;
        $this->escape=$escape;
    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    function validate($val, $field = '', $ctx = null)
    {
        $data=UserModel::getInstance()->fetchOne(array('$or' => array(
            array('em' => $ctx['em']),
            array('n'=>$ctx['n']) )
        ));
        if(!empty($this->escape) && is_callable($this->escape) && call_user_func($this->escape,$data)){
            return true;
        }
        $ret=empty($data) || ($ctx['em'] == $ctx['n']);
        return $ret;
    }
}