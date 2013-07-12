<?php
/**
 * For string use
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午9:05
 */
class Validator_Length extends Validator_Base
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
        $count=preg_match_all('/./mu',$val);
        $rangeValidator = AppValidators::newRange(array(
            '$gt'=>$this->gt,
            '$ge'=>$this->ge,
            '$le'=>$this->le,
            '$lt'=>$this->lt
        ), $this->errorMsg);
        return $rangeValidator->validate($count, $field, $ctx);
    }
}