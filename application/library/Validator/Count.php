<?php
/**
 * User: wangfeng
 * Date: 13-7-27
 * Time: 下午3:39
 */
class Validator_Count extends Validator_Base
{
    public $gt;

    public $ge;

    public $le;

    public $lt;

    public function checkParams()
    {

    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    public function validate($val, $field = '', $ctx = null)
    {
        $count = count($val);
        $rangeValidator = AppValidators::newRange(array(
            '$gt'=>$this->gt,
            '$ge'=>$this->ge,
            '$le'=>$this->le,
            '$lt'=>$this->lt
        ), $this->errorMsg);
        return $rangeValidator->validate($count, $field, $ctx);
    }
}