<?php
/**
 * User: wangfeng
 * Date: 13-6-3
 * Time: 下午9:07
 */
require_once __DIR__.'/../DipeiTestCase.php';
define('TEST_WINDOW',10);

class TestBaseModel extends  DipeiTestCase
{
    /**
     * @var TestModel
     */
    protected $model;

    public function setUp()
    {
        parent::setUp();
        require_once __DIR__.'/TestModel.php';
        $this->model=new TestModel();
        $this->assertNotEmpty($this->model->getCollection());
        $this->assertEquals(0, $this->model->count());
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->assertNotEmpty($this->model->getCollection());
        $this->model->getCollection()->drop();
    }

    /**
     *
     */
    public function insertProvider()
    {
        return array(
            array(
                array( 'name'=>'foo', '_id'=>1 ),
                false,
                1),
            array(
                array( 'name'=>'bar', '_id'=>2 ),
                false,
                1),
            array(
               array(
                   array( 'name'=>'foo2', '_id'=>3 ),
                   array( 'name'=>'bar2', '_id'=>4 )
                ),
                true,
                2)
        );
    }

    /**
     * @dataProvider insertProvider
     */
    public function testInsert($data,$batch,$expectedCount)
    {
        $this->model->insert($data, $batch);
        $this->assertEquals($expectedCount, $this->model->count());
    }

    public function prepareDatas()
    {
        $testWindow=TEST_WINDOW;
        for($i=0;$i<$testWindow;$i++){
            $this->model->insert(array('name'=>'foo'.$i,'_id'=>$i));
        }
        $this->assertEquals($testWindow,$this->model->getCollection()->count());
    }

    public function testFetch(){
        $this->prepareDatas();
        $datas=$this->model->fetch();
        $this->assertCount(TEST_WINDOW, $datas);
        for($i=0;$i<TEST_WINDOW;$i++){
            $this->assertEquals("foo$i",$datas[$i]['name']);
            $this->assertEquals("$i",$datas[$i]['_id']);
        }
        //test fetch with data
        $no5 = $this->model->fetchOne(array('_id'=>5),array('name'=>false));
        $this->assertEquals(5, $no5['_id']);
        $this->assertFalse(isset($no5['name']));
    }

    /**
     * @depends testFetch
     */
    public function testRemove()
    {
        $this->prepareDatas();
        $datas=$this->model->fetch();
        $this->model->remove($datas[5]);//remove no5

        $no5=$this->model->fetchOne(array('_id'=>$datas[5]['_id']));
        var_dump($no5);
        $this->assertEmpty($no5);

        $this->assertEquals(TEST_WINDOW - 1, $this->model->count());
    }

    /**
     * @depends testFetch
     */
    public function testUpdate()
    {
        $this->prepareDatas();
        $datas=$this->model->fetch();
        $no1 = $datas[1];
        $no1['name'] = 'bar';
        $this->model->update($no1);

        $afterNo1=$this->model->fetchOne(array('_id' => $no1['_id']));
        $this->assertEquals($no1['name'], $afterNo1['name']);

        //test with find
        $no2 = $datas[2];
        $no2['name'] = 'bar2';
        $no2Id = $no2['_id'];
        unset($no2['_id']);
        $this->model->update($no2, array('name'=>'foo2'));
        $afterNo2 = $this->model->fetchOne(array('_id' => $no2Id));
        $this->assertEquals($no2['name'], $afterNo2['name']);

    }

    /**
     * @dataProvider insertProvider
     */
    public function testSave($data,$batch,$expectedCount)
    {
        $this->model->save($data, $batch);
        $this->assertEquals($expectedCount, $this->model->count());
        //test update save
        $data=$batch?array_shift($data):$data;
        $data['name']='updatedName';
        $this->model->save($data);

        $afterData = $this->model->fetchOne(array('_id' => $data['_id']));
        $this->assertEquals($data['name'], $afterData['name']);
    }

    /**
     * @expectedException AppException
     * @expectedExceptionCode Constants::CODE_REMOVE_NEED_WHERE
     */
    public function testInvalidRemove(){
        $this->model->remove(array());
    }

    /**
     * @expectedException AppException
     * @expectedExceptionCode Constants::CODE_UPDATE_NEED_WHERE
     */
    public function testInvalidUpdate(){
        $this->model->update(array('name'=>'wangwang'));//must have find arg or has data _id
    }

    public function testValidate()
    {
        $stub=$this->getMock('TestModel',array('validate'));
        $stub->expects($this->any())->method('validate')->will($this->throwException(new AppException(Constants::CODE_INVALID_MODEL)));

        $testData=array('name'=>'wang');
        try{
            $stub->save($testData);
            fail();
        }catch (AppException $ex){
            $this->assertEquals(Constants::CODE_INVALID_MODEL, $ex->getCode());
        }
        try{
            $stub->insert($testData);
            fail();
        }catch (AppException $ex){
            $this->assertEquals(Constants::CODE_INVALID_MODEL, $ex->getCode());
        }
        try{
            $stub->update($testData);
            fail();
        }catch (AppException $ex){
            $this->assertEquals(Constants::CODE_INVALID_MODEL, $ex->getCode());
        }
    }

    public function formatProvider()
    {
        return array(
            array(array('n'=>'wang','s'=>1,'p'=>array('tit'=>'mytitle'))),
            array(array('n'=>'haa','s'=>2,'p'=>array('tit'=>'heetitle'))),
            array(array('n'=>'wang')),
            array(array('p'=>array('tit'=>'feebtitle')))
        );
    }

    /**
     * @dataProvider formatProvider
     */
    public function testFormatSchema($data)
    {
        $formatSchema=array(
            'n'=>'name',
            's'=>'sex',
            'p'=>array(
                'project',//outer name
                'tit'=>'title'
            )
        );
        $stub = $this->getMock('TestModel', array('getFormatSchema'));
        $stub->expects($this->any())->method('getFormatSchema')->will($this->returnValue($formatSchema));

        $formated = $stub->format($data);
        if(isset($data['n'])){
            $this->assertEquals($data['n'], $formated['name'],var_export($formated,true));
        }
        if(isset($data['s'])){
            $this->assertEquals($data['s'], $formated['sex'],var_export($formated,true));
        }
        if(isset($data['p'])){
//            var_export($formated);
            $this->assertTrue(isset($formated['project']), var_export($formated, true));
            $this->assertEquals($data['p']['tit'], $formated['project']['title'],var_export($formated,true));
        }
    }
}
