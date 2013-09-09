<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午11:09
 */
class DataSet extends PHPUnit_Framework_TestCase
{
    public function setUpTestLocations()
    {
        $testLocation=array(
            '_id'=>11,
            'n'=>'testlocation11',
            'pt'=>array(13)
        );
        $testLocation2=array(
            '_id'=>12,
            'n'=>'testlocation12',
            'pt'=>array(13)
        );
        $testLocation3=array(
            '_id'=>13,
            'n'=>'testlocation13',
            'pt'=>array()
        );
        LocationModel::getInstance()->insert(
            array($testLocation, $testLocation2,$testLocation3)
            , true
        );
    }

    public function setUpTestThemes()
    {
        $translationModel = TranslationModel::getInstance();

        $testTheme=array(
            '_id'=>101,
        );
        $testTheme2=array(
            '_id'=>102,
        );

        $translationModel->insert($testTheme);
        $translationModel->insert($testTheme2);
    }

    public function setUpTestUser()
    {
        $this->setUpTestLocations();
        $userModel=UserModel::getInstance();
        $user=array(
            '_id'=>1,
            'n'=>'wf',
            'em'=>'123@mail.com',
            'lid'=>11,
            'pw'=>444,
            'ctr'=>13,
            'l_t'=>Constants::LEPEI_HOST,
        );
        $user['_id']=$userModel->createUser($user);
        $userModel->updateUser($user);//ensure count
        return $user['_id'];
    }

    public function setUpFullTestUser()
    {
        $uid=$this->setUpTestUser();
        $this->setUpTestThemes();

        $projectInfo = array(
            '_id'=>1,
            'uid'=>$uid,
            't'=>'hello title',
            'tm' => array(101, 102),
            's' => Constants::STATUS_NEW,
            'ds' => array(
                array(
                    'ls' => array(11, 12),
                ),
                array(
                    'ls' => array(11)
                )
            ),
        );
        ProjectModel::getInstance()->addProject($projectInfo);
        return $uid;
    }
}