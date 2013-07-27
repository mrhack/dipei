<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午11:02
 */
require_once '../DipeiTestCase.php';
require_once 'TestRegController.php';
require_once '../models/TestProjectModel.php';

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
        $this->dataSet->setUpFullTestUser();
        $this->assertLogined(true);

        $project = ProjectModel::getInstance()->fetchOne();

        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('/profile/removeProject');
        $request->setPost(
            array( 'pid'=>$project['_id'])
        );
        $this->assertEquals($project['_id'],$request->getPost('pid'));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $afterProject = ProjectModel::getInstance()->fetchOne();
        $this->assertEquals(Constants::STATUS_DELETE, $afterProject['s']);
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