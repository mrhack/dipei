<?php
/**
 * User: wangfeng
 * Date: 13-7-14
 * Time: 下午9:54
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class TestLikeModel extends DipeiTestCase
{
    public function setUp(){
        parent::setUp();
        //add location
        LocationModel::getInstance()->insert(array(
            '_id'=>1,
            'n'=>'testLocation',
        ));
        //add user project
        $id=UserModel::getInstance()->createUser(array(
            'em'=>'wang@jj.com',
            'n'=>'wang',
            'pwd'=>'123'
        ));
        UserModel::getInstance()->addProject(array('_id' => $id),array(
            '_id'=>11,
            't'=>'test title',
            'lk'=>0
        ));
    }

    public function tearDown()
    {
        parent::tearDown();
        LocationModel::getInstance()->getCollection()->drop();
        UserModel::getInstance()->getCollection()->drop();
        LikeModel::getInstance()->getCollection()->drop();
    }

    public function testLikeLocation()
    {
        $likeModel=LikeModel::getInstance();
        $likeModel->like(0, Constants::LIKE_LOCATION, 1);
        $location = LocationModel::getInstance()->fetchOne();
        $this->assertEquals(1, $location['lk']);
        $like = LikeModel::getInstance()->fetchOne();
        $this->assertEquals(1,$like['am']);
        $this->assertEquals(0, $like['uid']);
        $this->assertEquals(1, $like['oid']);
        $this->assertTrue(isset($like['t']));
    }

    /**
     * @expectedException AppException
     * @expectedExceptionCode Constants::CODE_NOT_FOUND_LIKE_OBJECT
     */
    public function testInvalid()
    {
        $likeModel=LikeModel::getInstance();
        $likeModel->like(0, Constants::LIKE_LOCATION, 12);
    }

    public function testLikeProject()
    {
        $likeModel=LikeModel::getInstance();
        $likeModel->like(0, Constants::LIKE_PROJECT, 11);
        $user = UserModel::getInstance()->fetchOne();
        $project = UserModel::getInstance()->findProjectFromUser($user, 11);
        $this->assertEquals(1, $project['lk']);
    }
}