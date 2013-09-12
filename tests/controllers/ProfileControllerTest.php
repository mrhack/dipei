<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午11:02
 */
require_once  __DIR__.'/../DipeiTestCase.php';
require_once __DIR__.'/RegControllerTest.php';
require_once __DIR__.'/../models/ProjectModelTest.php';

class ProfileControllerTest extends DipeiTestCase
{
    public function setUp()
    {
        parent::setUp();
        UserModel::getInstance()->getCollection()->drop();
        LocationModel::getInstance()->getCollection()->drop();
        ProjectModel::getInstance()->getCollection()->drop();
        TranslationModel::getInstance()->getCollection()->drop();
    }

    public function tearDown()
    {
        parent::tearDown();
        UserModel::getInstance()->getCollection()->drop();
        LocationModel::getInstance()->getCollection()->drop();
        ProjectModel::getInstance()->getCollection()->drop();
        TranslationModel::getInstance()->getCollection()->drop();
    }

    public function testAddProject()
    {
        $this->dataSet->setUpTestUser();
        $this->dataSet->setUpTestThemes();
        $testRequest=new Test_Http_Request();
        $testRequest->method='POST';
        $testRequest->setRequestUri('/profile/addProject');
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
        $testReg=new RegControllerTest();
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

        unset($dbInput['n']);
        $this->assertArrayEquals($dbInput, $user);
        $this->assertEquals('wangfeng',$user['n']);
    }

}
