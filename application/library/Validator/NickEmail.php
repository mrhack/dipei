<?php
/**
 * User: wangfeng
 * Date: 13-7-29
 * Time: 下午8:30
 */
class Validator_NickEmail extends Validator_Base
{
    public function __construct($errorMsg){
        $this->errorMsg=$errorMsg;
    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    function validate($val, $field = '', $ctx = null)
    {
        if($ctx['em'] == $ctx['n']) return false;
        $data=UserModel::getInstance()->fetchOne(array('$or' => array(
            array('em' => $ctx['em']),
            array('n'=>$ctx['n']) )
        ));
        $ret=empty($data);
        return $ret;
    }
}