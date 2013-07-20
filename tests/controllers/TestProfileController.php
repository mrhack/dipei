<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午11:02
 */
require_once '../DipeiTestCase.php';

class TestProfileController extends DipeiTestCase
{
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
}