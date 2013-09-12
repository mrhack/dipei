<?php
/**
 * User: wangfeng
 * Date: 13-7-14
 * Time: 下午9:54
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class LikeModelTest extends DipeiTestCase
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
        ProjectModel::getInstance()->addProject(array(
            '_id'=>11,
            'uid'=>$id,
            'ds'=>'desc something',
            't'=>'test title',
            's'=>Constants::STATUS_NEW,
            'lk'=>0
        ));
    }

    public function tearDown()
    {
        parent::tearDown();
        LocationModel::getInstance()->getCollection()->remove(array());
        UserModel::getInstance()->getCollection()->remove(array());
        LikeModel::getInstance()->getCollection()->remove(array());
        ProjectModel::getInstance()->getCollection()->remove(array());
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

    public function testInvalidLike()
    {
        $likeModel=LikeModel::getInstance();
        try{
            $likeModel->like(0, 0, 1);
            $this->fail('err');
        }catch (Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function testDuplicateLike()
    {
        $this->testLikeLocation();
        $likeModel=LikeModel::getInstance();
        try{
            $likeModel->like(0, Constants::LIKE_LOCATION, 1);
            $this->fail('not caught duplicate key error');
        }catch (AppException $ex){
        }
        //assert not add count
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
        $likeId=$likeModel->like(0, Constants::LIKE_PROJECT, 11);
        $project = ProjectModel::getInstance()->fetchOne();
        $this->assertEquals(1, $project['lk']);
        return $likeId;
    }

    /**
     */
    public function testUnLike()
    {
        //test to unlike a project
        $likeId=$this->testLikeProject();
        $likeModel=LikeModel::getInstance();
        $likeModel->unlike(0,Constants::LIKE_PROJECT,11);
        $this->assertEmpty($likeModel->fetchOne(array('_id'=>$likeId)));
        //assert count
        $project = ProjectModel::getInstance()->fetchOne();
        $this->assertEquals(0, $project['lk']);
    }
}
