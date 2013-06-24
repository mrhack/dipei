<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午2:40
 * @method static Helper_Local getInstance()
 */
class Helper_Local
{
    use Strategy_Singleton;

    public function isChinaLocal($localName)
    {
        if(stripos($localName,Constants::LANG_ZH) === 0){
            return true;
        }else{
            return false;
        }
    }

    public function getParentLocal($localName){
        return preg_replace('/(\w+)_\w+/','$1',$localName);
    }
}