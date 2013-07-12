<?php
/**
 * For numeric use
 * User: wangfeng
 * Date: 13-7-12
 * Time: 上午11:54
 */
class Validator_Range extends Validator_Base
{
    public $in;//

    public $lt;//less than

    public $le;

    public $gt;//greater than

    public $ge;

    public function checkParams()
    {
        //TODO check lt,gt,ge,le valid
    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    function validate($val, $field='', $ctx = null)
    {
        $ret=false;
        if(!is_null($this->in)){
            $ret = in_array($val,$this->in);
        }

        if(isset($this->gt) || isset($this->ge)){
            if(isset($this->gt)){
                $ret = ($val > $this->gt);
            }else{
                $ret = ($val >= $this->ge);
            }
            if(isset($this->lt)){
                $ret &= ($val < $this->lt);
            }else if(isset($this->le)){
                $ret &= ($val <= $this->le);
            }
        }else{
            if(isset($this->lt)){
                $ret = ($val < $this->lt);
            }else if(isset($this->le)){
                $ret = ($val <= $this->le);
            }
        }
        return (bool)$ret;
    }
}
