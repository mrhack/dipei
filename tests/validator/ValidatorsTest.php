<?php
/**
 * User: wangfeng
 * Date: 13-7-12
 * Time: 下午4:29
 */
require_once  __DIR__.'/../DipeiTestCase.php';

class ValidatorsTest extends DipeiTestCase
{
    public function rangeProvider()
    {
        return array(
            //in mode
            array(array(22,55,88),'errorMsg',22,true),
            array(array(22,55,88),'errorMsg',12,false),
            //gt and ge mode
            array(array('$gt'=>11,'$le'=>20),'errorMsg',20,true),
            array(array('$gt'=>11,'$le'=>20),'errorMsg',30,false),
            array(array('$gt'=>11,'$le'=>20),'errorMsg',-10,false),
        );
    }

    /**
     * @dataProvider rangeProvider
     */
    public function testNewRange($exp,$msg,$val,$expectResult)
    {
        $validator = AppValidators::newRange($exp, $msg);
        $this->assertEquals($expectResult, $validator->validate($val));
        $this->assertEquals($msg, $validator->errorMsg);
    }

    public function regexProvider()
    {
        return array(
            array('/^\d+$/','must be number!','123443345',true),
            array('/^\d+$/','must be number!','1234adfe43345',false)
        );
    }

    /**
     * @dataProvider regexProvider
     */
    public function testNewRegex($exp,$msg,$val,$expectResult)
    {
        $validator=AppValidators::newRegexp($exp,$msg);
        $this->assertEquals($expectResult, $validator->validate($val));
        if(!$expectResult)//assert set errorMsg
            $this->assertEquals($msg, $validator->errorMsg);
    }


    public function testNewUnique()
    {
        $userModel=UserModel::getInstance();

        $validator = AppValidators::newUnique($userModel,'name must be unique!');
        //before insertion
        $this->assertTrue($validator->validate('wang', 'n'));

        $uid=$userModel->createUser(array(
            'n'=>'wang',
            'em'=>'wang@lepei.com',
            'pw'=>'abc'
        ));
        $this->assertNotEmpty($userModel->fetchOne(array('n' => 'wang')));
        $this->assertNotEmpty($userModel->fetchOne(array('em' => 'wang@lepei.com')));
        //after insertion
        $this->assertFalse($validator->validate('wang', 'n'));
        $this->assertEquals('name must be unique!', $validator->errorMsg);

        //test escape
        $validator = AppValidators::newUnique($userModel, 'name must be unique!', function($data) use($uid){
            return $data['_id'] === $uid;
        });
        $this->assertTrue($validator->validate('wang', 'n'));
        $this->assertTrue($validator->validate('wang@lepei.com', 'em'));
    }

    public function lengthProvider()
    {
        return array(
            array(array('$ge'=>5,'$le'=>8),'wangf',true),
            array(array('$le'=>3),'中国心',true),
            array(array('$gt'=>3),'中国心',false),
        );
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testNewLength($exp,$val,$expectResult)
    {
        $validator = AppValidators::newLength($exp);
        $this->assertEquals($expectResult, $validator->validate($val));
    }

    public function countProvider()
    {
        return array(
            array(array('$ge'=>5,'$le'=>8),range(1,6),true),
            array(array('$le'=>3),range(1,3),true),
            array(array('$gt'=>3),array(),false),
        );
    }

    /**
     * @dataProvider countProvider
     */
    public function testNewCount($exp,$val,$expectResult)
    {
        $validator = AppValidators::newCount($exp);
        $this->assertEquals($expectResult, $validator->validate($val));
    }
}
