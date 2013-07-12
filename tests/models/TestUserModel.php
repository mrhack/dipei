<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:21
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class TestUserModel extends DipeiTestCase
{

    public function testCreateTestUser()
    {
        $userModel=new UserModel();
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

        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
        $this->assertEquals(1, $afterTestLocation1['c']['d']);
        $this->assertEquals(1, $afterTestLocation1['c']['p']);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(0, $afterTestLocation2['c']['d']);
        $this->assertEquals(2, $afterTestLocation2['c']['p']);
        $this->assertEquals(2, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1, $afterTestLocation3['c']['d']);
        $this->assertEquals(2, $afterTestLocation3['c']['p']);
        $this->assertEquals(2, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(1,$afterTestLocation3['tm_c'][102]);


        //modify lid
        $user['lid']=12;
        $userModel->updateUser($user);
        $afterTestLocation1 = $locationModel->fetchOne(array('_id' => 11));
        $this->assertEquals(0,$afterTestLocation1['c']['d']);
        $this->assertEquals(1,$afterTestLocation1['c']['p']);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(1,$afterTestLocation2['c']['d']);
        $this->assertEquals(2,$afterTestLocation2['c']['p']);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1,$afterTestLocation3['c']['d']);
        $this->assertEquals(2,$afterTestLocation3['c']['p']);

        //remove project
        $afterUser = $userModel->fetchOne(array('_id'=>$user['_id']));
        $userModel->removeProject($afterUser, $afterUser['ps'][1]['_id']);
        $afterTestLocation1 = $locationModel->fetchOne(array('_id' => 11));
        $this->assertEquals(1, $afterTestLocation1['c']['p']);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(1, $afterTestLocation2['c']['p']);
        $this->assertEquals(1, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1, $afterTestLocation3['c']['p']);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][102]);

        //modify project
        $afterUser = $userModel->fetchOne(array('_id'=>$user['_id']));
        $afterUser['ps'][0]['tm']=array(102);
        $afterUser['ps'][0]['ds'][0]['ls'] = array(11);
        $userModel->updateProject($afterUser, $afterUser['ps'][0]['_id']);

        $afterTestLocation1 = $locationModel->fetchOne(array('_id' => 11));
        $this->assertEquals(1, $afterTestLocation1['c']['p']);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(0, $afterTestLocation2['c']['p']);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1, $afterTestLocation3['c']['p']);
        $this->assertEquals(0, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][102]);

        //add project
        $afterUser = $userModel->fetchOne(array('_id' => $user['_id']));
        $newProject=array(
            'tm'=>array(101),
            'ds'=>array(
                array(
                    'ls'=>array(12)
                ),
                array(
                    'ls'=>array(12)
                )
            )
        );
        $userModel->addProject($afterUser, $newProject);
        $afterTestLocation1 = $locationModel->fetchOne(array('_id' => 11));
        $this->assertEquals(1, $afterTestLocation1['c']['p']);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(1, $afterTestLocation2['c']['p']);
        $this->assertEquals(1, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(2, $afterTestLocation3['c']['p']);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][102]);
    }
}
