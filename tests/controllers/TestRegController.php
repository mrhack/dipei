<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午10:14
 */
require_once '../DipeiTestCase.php';

class TestRegController extends DipeiTestCase
{
    public function testReg()
    {
        $request=new Yaf_Request_Simple();
        $request->setRequestUri('/reg');
        $request->method = 'POST';
        $_POST=array(
            'n'=>'wangfeng',
            'em'=>'wangfeng@leipei.com',
            'pw'=>'12345'
        );
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->expectOutputRegex('/"err":0/');
        //test auto logined
        $this->assertLogined(true);

        $userModel=UserModel::getInstance();
        $this->assertEquals(1, $userModel->count());
    }

    /**
     * @depends testReg
     */
    public function testLogout()
    {
        $this->assertLogined(true);
        $this->dispatch('/login/logout');
        $this->expectOutputRegex('/"err":0/');
        $this->assertLogined(false);
    }

    /**
     * @depends testLogout
     */
    public function testLogin()
    {
        $this->assertLogined(false);
        $request=new Yaf_Request_Simple();
        $request->method = 'POST';
        $request->setRequestUri('/login');
        $_POST=array(
            'em'=>'wangfeng@lepei.com',
            'pw'=>'12345'
        );
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->expectOutputRegex('/"err":0/');
        $this->assertLogined(true);
    }

}