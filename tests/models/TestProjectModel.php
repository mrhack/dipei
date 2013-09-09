<?php
/**
 * User: wangfeng
 * Date: 13-7-27
 * Time: 下午1:53
 */
require_once '../DipeiTestCase.php';
class TestProjectModel extends DipeiTestCase
{
    public function testAddProject()
    {
        $this->dataSet->setUpTestUser();
        $projectInfo = array(
            'uid'=>1,
            'tm' => array(101, 102),
            't'=>'how are you?--title',
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
        $this->assertEquals(0,ProjectModel::getInstance()->count());
        $this->assertEquals(0, FeedModel::getInstance()->count());
        $pid=ProjectModel::getInstance()->addProject($projectInfo);
        $this->assertEquals(1,ProjectModel::getInstance()->count());
        //
        $locationModel=LocationModel::getInstance();
        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
        $this->assertEquals(0, $afterTestLocation1['c']['p']);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(0, $afterTestLocation2['c']['p']);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(0, $afterTestLocation3['c']['p']);
        $this->assertEquals(0, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(0,$afterTestLocation3['tm_c'][102]);

        $afterUser = UserModel::getInstance()->fetchOne();
        $this->assertEquals(array($pid), $afterUser['ps']);
        $this->assertEquals(0, $afterUser['pc']);

        //assert feed
        $feed = FeedModel::getInstance()->fetchOne();
        $expedted=array(
            'oid'=>1,
            'tp'=>Constants::FEED_TYPE_PROJECT,
            'uid'=>1,
            'lpt'=>array(13,11),
            's'=>Constants::STATUS_NEW
        );
        var_dump($feed);
        $this->assertArrayEquals($expedted, $feed);
        return $pid;
    }

    /**
     * @depends testAddProject
     */
    public function testPassProject($pid)
    {
        $projectInfo = ProjectModel::getInstance()->fetchOne(array('_id' => $pid));
        ProjectModel::getInstance()->passProject($projectInfo);

        $locationModel=LocationModel::getInstance();
        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
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
        $this->assertEquals(1,$afterTestLocation3['tm_c'][102]);

        $afterUser = UserModel::getInstance()->fetchOne();
        $this->assertEquals(array($pid), $afterUser['ps']);
        $this->assertEquals(1, $afterUser['pc']);

        //assert feed
        $feed=FeedModel::getInstance()->fetchOne();
        $this->assertEquals(Constants::STATUS_PASSED, $feed['s']);
        return $pid;
    }

    /**
     * @depends testPassProject
     */
    public function testUpdateProject($pid)
    {
        $updateProjectInfo=array(
            '_id'=>$pid,
            'tm'=>array(101),
            's'=>Constants::STATUS_PASSED,
            'ds'=>array(
                array(
                    'ls'=>array(12)
                ),
                array(
                    'ls'=>array(12)
                )
            )
        );
        ProjectModel::getInstance()->updateProject($updateProjectInfo);

        $locationModel=LocationModel::getInstance();
        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
        $this->assertEquals(0, $afterTestLocation1['c']['p']);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(1, $afterTestLocation2['c']['p']);
        $this->assertEquals(1, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(1, $afterTestLocation3['c']['p']);
        $this->assertEquals(1, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(0,$afterTestLocation3['tm_c'][102]);

        $afterUser = UserModel::getInstance()->fetchOne();
        $this->assertEquals(array($pid), $afterUser['ps']);
        $this->assertEquals(1, $afterUser['pc']);
        return $pid;
    }

    /**
     * @depends testUpdateProject
     */
    public function testRemoveProject($pid)
    {
        $projectInfo = ProjectModel::getInstance()->fetchOne(array('_id' => $pid));
        ProjectModel::getInstance()->removeProject($projectInfo);

        //assert status
        $afterProject = ProjectModel::getInstance()->fetchOne(array('_id' => $pid));
        $this->assertEquals(Constants::STATUS_DELETE, $afterProject['s']);

        $locationModel=LocationModel::getInstance();
        $afterTestLocation1 = $locationModel->fetchOne(array('_id'=>11));
        $this->assertEquals(0, $afterTestLocation1['c']['p']);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation1['tm_c'][102]);

        $afterTestLocation2 = $locationModel->fetchOne(array('_id' => 12));
        $this->assertEquals(0, $afterTestLocation2['c']['p']);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][101]);
        $this->assertEquals(0, $afterTestLocation2['tm_c'][102]);

        $afterTestLocation3 = $locationModel->fetchOne(array('_id' => 13));
        $this->assertEquals(0, $afterTestLocation3['c']['p']);
        $this->assertEquals(0, $afterTestLocation3['tm_c'][101]);
        $this->assertEquals(0,$afterTestLocation3['tm_c'][102]);

        $afterUser = UserModel::getInstance()->fetchOne();
        $this->assertEquals(array(), $afterUser['ps']);
        $this->assertEquals(0, $afterUser['pc']);


        $feed = FeedModel::getInstance()->fetchOne();
        $this->assertEquals(Constants::STATUS_DELETE, $feed['s']);
    }
}