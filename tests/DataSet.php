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

    public function setUpTestUser()
    {
        $this->setUpTestLocations();
        $userModel=new UserModel();

        $user=array(
            'n'=>'wf',
            'em'=>'123@mail.com',
            'lid'=>11,
            'pw'=>444,
            'l_t'=>Constants::LEPEI_HOST,
            'ps'=>array(
                array(
                    'tm'=>array(101,102),
                    'ds'=>array(
                        array(
                            'ls'=>array(11,12),
                        ),
                        array(
                            'ls'=>array(11)
                        )
                    ),
                ),
                array(
                    'tm'=>array(101),
                    'ds'=>array(
                        array(
                            'ls'=>array(12)
                        ),
                        array(
                            'ls'=>array(12)
                        )
                    )
                )
            )
        );
        $user['_id']=$userModel->createUser($user);
        $userModel->updateUser($user);//ensure count
    }
}