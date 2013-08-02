<?php
/**
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午4:57
 */
class Validator_Regex extends Validator_Base
{
    public $regexps;

    public function checkParams()
    {
        if(empty($this->regexps)){
            throw new AppException(Constants::CODE_PARAM_INVALID,'empty regexps!');
        }
        foreach($this->regexps as $regex=>$errMsg){
            if(preg_match($regex, '') === false){
                throw new AppException(Constants::CODE_PARAM_INVALID, "invalid regexps $regex,error :" . preg_last_error());
            }
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
        foreach($this->regexps as $regex=>$errMsg){
            if(preg_match($regex, $val) === 0){
                $this->errorMsg=$errMsg;
                return false;
            }
        }
        return true;
    }
}