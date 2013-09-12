<?php
/**
 * User: wangfeng
 * Date: 13-7-29
 * Time: 下午8:37
 */
require_once  __DIR__.'/../DipeiTestCase.php';

class NickEmailTest extends DipeiTestCase
{
    public function setUp()
    {
        parent::setUp();
        UserModel::getInstance()->getCollection()->remove(array());
    }

    public function testValidate1()
    {
        $user=array(
            '_id'=>1,
            'em'=>'wangfeng@lepei.com',
            'n'=>'wangfeng@lepei.com'
        );
        $validator = new Validator_NickEmail('email name duplicate');
        $ret=$validator->validate($user['em'],'em',$user);
        $this->assertFalse($ret);
        $this->assertEquals('email name duplicate', $validator->errorMsg);
    }

    public function testEscape()
    {
        $user=array(
            '_id'=>1,
            'em'=>'wangfeng@lepei.com',
            'n'=>'wangfeng@lepei.com'
        );
        $escape=function(){
            return true;
        };
        $validator = new Validator_NickEmail('email name duplicate',$escape);
        $ret=$validator->validate($user['em'],'em',$user);
        $this->assertTrue($ret);
    }

    public function validateProvider()
    {
        return array(
            array(
                array(array('em'=>'wang@lepei.com','n'=>'wang@lepei.com')),
                array(false)
            ),
            array(
                array(array('em'=>'wang@lepei.com','n'=>'wang'),array('em'=>'feng@lepei.com','n'=>'wang@lepei.com'),array('em'=>'wang@lepei.com','n'=>'wang')),
                array(true,true,false)
            ),
        );
    }

    /**
     * @dataProvider validateProvider
     */
    public function testValidate2($users,$expects)
    {
        foreach($users as $k=>$user){
            $ok=false;
            try{
                UserModel::getInstance()->createUser($user);
                $ok=true;
            }catch (Exception $ex){
                $ok=false;
                $this->assertEquals(Constants::CODE_INVALID_MODEL, $ex->getCode());
            }
            $this->assertEquals($expects[$k], $ok);
        }
    }
}
