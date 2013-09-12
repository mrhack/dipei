<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:21
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class UserModelTest extends DipeiTestCase
{

    public function testCreateTestUser()
    {
        $userModel=UserModel::getInstance();
        $uid=$userModel->createUser(array(
            'n'=>'wf',
            'em'=>'sdf@dks.com',
            'pw'=>'3432'
        ));
        $user=Yaf_Session::getInstance()['user'];
        $this->assertNotEmpty($user);
        $this->assertEquals($uid, $user['_id']);
        $userModel->remove(array('_id' => $uid));
    }

    public function testUpdateUser()
    {
        $testLocation=array(
            '_id'=>11,
            'pt'=>array(13)
        );
        $testLocation2=array(
            '_id'=>12,
            'pt'=>array(13)
        );
        $testLocation3=array(
            '_id'=>13,
            'pt'=>array()
        );

        $locationModel =LocationModel::getInstance();
        $translationModel = TranslationModel::getInstance();
        $userModel=UserModel::getInstance();

        $testTheme=array(
            '_id'=>101,
        );
        $testTheme2=array(
            '_id'=>102,
        );

        $translationModel->insert($testTheme);
        $translationModel->insert($testTheme2);

        $locationModel->insert($testLocation);
        $locationModel->insert($testLocation2);
        $locationModel->insert($testLocation3);

        $user=array(
            'n'=>'wf',
            'em'=>'123@mail.com',
            'lid'=>11,
            'pw'=>444,
            'l_t'=>Constants::LEPEI_HOST,
        );
        $user['_id']=$userModel->createUser($user);
        $userModel->updateUser($user);//ensure count

        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
        $this->assertEquals(1, $afterTestLocation1['c']['d']);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(0, $afterTestLocation2['c']['d']);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1, $afterTestLocation3['c']['d']);

        //modify lid
        $user['lid']=12;
        $userModel->updateUser($user);
        $afterTestLocation1 = $locationModel->fetchOne(array('_id' => 11));
        $this->assertEquals(0,$afterTestLocation1['c']['d']);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(1,$afterTestLocation2['c']['d']);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1,$afterTestLocation3['c']['d']);
    }

    public function testIncCount()
    {
        $userModel=UserModel::getInstance();
        $user = $userModel->fetchOne();
        $userModel->incCount($user['_id'],'msgs.m');
        $after = $userModel->fetchOne(array('_id' => $user['_id']));

        $this->assertEquals(1, $after['msgs']['m']);
        return $after['_id'];
    }

    /**
     * @depends testIncCount
     */
    public function testClearCount($uid)
    {
        $userModel=UserModel::getInstance();
        $userModel->clearCount($uid,'msgs.m');

        $after=$userModel->fetchOne(array('_id'=>$uid));

        $this->assertEquals(0, $after['msgs']['m']);
    }
}
