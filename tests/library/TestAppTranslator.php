<?php
/**
 * User: wangfeng
 * Date: 13-6-14
 * Time: 下午10:20
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class TestAppTranslator extends DipeiTestCase
{
    public function testTranslate()
    {
        $translator = AppTranslator::getInstance();
        $locationModel=LocationModel::getInstance();
        $cursor = $locationModel->fetch();
        $i=0;
        foreach($cursor as $location){
            if($i++ == 10){
                break;
            }
            echo $location['n']."=>".$translator->translate(Constants::LANG_ZH_CN, Constants::LANG_EN, $location['n']),"\n";
        }
    }

    public function testPinyin()
    {
        $translator=AppTranslator::getInstance();
        $this->assertEquals('zhongguo', $translator->translatePinyin('中国'));
        $this->assertEquals('huangdegang', $translator->translatePinyin('黄的刚'));
    }

}