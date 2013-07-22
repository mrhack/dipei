<?php
/**
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午4:09
 */
class AppValidators
{
    /**
     * exp1:
     * array(1,2,3) // 校验值为其中之一
     * exp2:
     * array($gt=>1,$le=>10) //校验值>1并且<=10
     * @param array $exp
     * @param $errMsg
     */
    public static function newRange($exp,$errMsg='')
    {
        $cfg = self::_getRangeConfig($exp, $errMsg);
        return new Validator_Range($cfg);
    }

    private static function _getRangeConfig($exp,$errMsg)
    {
        $cfg=array(
            'errorMsg'=>$errMsg
        );
        $strip=array('$gt','$lt','$le','$ge');
        foreach($exp as $k=>$v){
            if(in_array($k,$strip,true)){
                unset($exp[$k]);
                $cfg[substr($k, 1)]=$v;
            }
        }
        if(!empty($exp)){
            $cfg['in']=$exp;
        }
        return $cfg;
    }

    /**
     * regexps:
     * array(
     * '/reg1/' => 'not pass reg1',
     * '/reg2/' => 'not pass reg2',
     * )
     * @param $regexps
     */
    public static function newRegexps($regexps)
    {
        $cfg=array(
            'regexps'=>$regexps
        );
        return new Validator_Regex($cfg);
    }

    /**
     * @param $regex
     * @param string $errMsg
     */
    public static function newRegexp($regex,$errMsg='')
    {
        return self::newRegexps(array($regex => $errMsg));
    }

    public static function newUnique($model,$errMsg='',$escape=array())
    {
        $cfg=array(
            'model'=>&$model,
            'escape'=>$escape,
            'errorMsg'=>$errMsg
        );
        return new Validator_Unique($cfg);
    }

    /**
     * 规则同range,但是以字符串长度为计算标准
     * @param $exp
     * @param string $errMsg
     */
    public static function newLength($exp,$errMsg='')
    {
        $cfg = self::_getRangeConfig($exp, $errMsg);
        return new Validator_Length($cfg);
    }

    public static function newRequired($errMsg='')
    {
        return self::newLength(array('$gt' => 0), $errMsg);
    }

}