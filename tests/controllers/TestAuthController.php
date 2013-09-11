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
       $this->assertEquals(1, $user['as']);

       $location=LocationModel::getInstance()->fetchOne(array('_id'=>11));
       $this->assertEquals(1,$location['c']['d']);
    }

    /**
     * @depends testAuth1
     */
    public function testAuth2()
    {
        $this->dataSet->setUpTestThemes();
        $testRequest=new Test_Http_Request();
        $testRequest->method='POST';
        $testRequest->setRequestUri('/auth');
        $input=array(
            'uid'=>1,
            'travel_themes' => array(101, 102),
            'title'=>'how are you?--title',
            'status' => Constants::STATUS_NEW,
            'days' => array(
                array(
                    'lines' => array(11, 12),
                ),
                array(
                    'lines' => array(11)
                )
            ),
        );
        $testRequest->setPost($input);
        $this->getYaf()->getDispatcher()->dispatch($testRequest);

        $dbInput = ProjectModel::getInstance()->format($input, true);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
        $this->assertArrayEquals($dbInput, ProjectModel::getInstance()->fetchOne());

        $location=LocationModel::getInstance()->fetchOne(array('_id'=>11));
        $this->assertEquals(1,$location['c']['d']);
    }
}