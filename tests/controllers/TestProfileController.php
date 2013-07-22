<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午11:02
 */
require_once '../DipeiTestCase.php';
require_once 'TestRegController.php';

class TestProfileController extends DipeiTestCase
{

    public function setUp()
    {
        parent::setUp();
        UserModel::getInstance()->getCollection()->drop();
        LocationModel::getInstance()->getCollection()->drop();
    }

    public function tearDown()
    {
        parent::tearDown();
        UserModel::getInstance()->getCollection()->drop();
        LocationModel::getInstance()->getCollection()->drop();
    }

    public function testRemoveProject()
    {
        $this->dataSet->setUpTestUser();
        $this->assertLogined(true);

        $user = UserModel::getInstance()->fetchOne();

        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('/profile/removeProject');
        $request->setPost(
            array( 'pid'=>$user['ps'][0]['_id'] )
        );
        $this->assertEquals($user['ps'][0]['_id'],$request->getPost('pid'));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $afterUser = UserModel::getInstance()->fetchOne();
        $project = UserModel::getInstance()->findProjectFromUser($afterUser, $user['ps'][0]['_id']);
        $this->assertEmpty($project);//assert project unexists
    }

    public function testSettingAction()
    {
        $testReg=new TestRegController();
        $testReg->testReg();
        $this->dataSet->setUpTestLocations();

        //
        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('/profile/setting');
        $input=array(
            'name'=>'upName',
            'sex'=>Constants::SEX_MALE,
            'birth'=>array(
                'year'=>1990,
                'month'=>4,
                'day'=>1
            ),
            'lid'=>11,
            'country'=>13
        );
        $request->setPost($input);

        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $userModel=UserModel::getInstance();
        $dbInput = $userModel->format($input, true);
        $user = $userModel->fetchOne();

        $this->assertArrayEquals($dbInput, $user);
    }
}