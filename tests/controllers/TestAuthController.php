<?php
/**
 * User: wangfeng
 * Date: 13-7-22
 * Time: 下午2:51
 */
require_once '../DipeiTestCase.php';
require_once 'TestRegController.php';

class TestAuthController extends DipeiTestCase
{
    public function testAuth1()
    {
       $regTest=new TestRegController();
       $regTest->testReg();
        //
       $this->dataSet->setUpTestLocations();
       $request=new Test_Http_Request();
       $request->method = 'Post';
       $request->setRequestUri('/auth');
       $testInput= array(
           'lepei_type'=>Constants::LEPEI_PROFESSIONAL,
            'langs'=>array(Constants::LANGUAGE_CHINESE=>Constants::FAMILIAR_FREQUENT),
            'contacts'=>array(
                Constants::CONTACT_EMAIL=>'test_email@qq.com',
                Constants::CONTACT_WEIXIN=>'test_weixin',
                Constants::CONTACT_TEL=>'1356434234',
                Constants::CONTACT_QQ=>'test_qq',
            ),
           'lid'=>11,
           'desc'=>'test desc',
       );
       $request->setPost($testInput);
       $this->getYaf()->getDispatcher()->dispatch($request);
       $this->assertAjaxCode(Constants::CODE_SUCCESS);
       //assert user setting ok
       $user = UserModel::getInstance()->fetchOne();
       $dbInput = UserModel::getInstance()->format($testInput, true);
       $this->assertArrayEquals($dbInput, $user);
    }

    /**
     * @depends testAuth1
     */
    public function testAuth2()
    {
        
    }
}