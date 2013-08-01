<?php
/**
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午6:10
 */
class Validator_Unique extends Validator_Base
{
    /**
     * @var BaseModel
     */
    public $model;

    /**
     * @var callable
     */
    public $escape;

    public function checkParams()
    {
        if(!$this->model instanceof BaseModel){
            throw new AppException(Constants::CODE_PARAM_INVALID,'invalid unique model instance class:'.get_class($this->model));
        }
    }

    /**
     * @param $val 值
     * @param $field 字段名
     * @param null $ctx 关联数据。通常为model数据实体
     * @return bool
     */
    function validate($val, $field = '', $ctx = null)
    {
        $data=$this->model->fetchOne(array($field=>$val));
        if(!empty($this->escape) && is_callable($this->escape) && call_user_func($this->escape,$data)){
            return true;
        }
        $ret = empty($data);
        return $ret;
    }
}