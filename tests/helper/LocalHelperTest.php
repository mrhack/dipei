<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午2:42
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class LocalHelperTest extends DipeiTestCase
{
    public function chinaLocalProvider()
    {
        return array(
            array(Constants::LANG_ZH,true),
            array(Constants::LANG_EN,false),
            array(Constants::LANG_ZH_HANS_CN,true)
        );
    }

    /**
     */
    public function testIsChinaLocal()
    {
        foreach($this->chinaLocalProvider() as $params){
            list($local,$isChinaLocal) =$params;
            $this->assertEquals($isChinaLocal, Helper_Local::getInstance()->isChinaLocal($local),$local);
        }
    }
}
