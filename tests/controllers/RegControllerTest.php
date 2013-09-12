<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午10:14
 */
require_once  __DIR__.'/../DipeiTestCase.php';

class RegControllerTest extends DipeiTestCase
{
    public function testReg()
    {
        $request=new Test_Http_Request();
        $request->setRequestUri('/reg');
        $request->method = 'POST';
        $request->setPost(array(
            'name' => 'wangfeng',
            'email' => 'wangfeng@lepei.com',
            'password' => '12345'
        ));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
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
        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('/login');
        $request->setPost(array(
            'email'=>'wangfeng@lepei.com',
            'password'=>'12345'
        ));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->expectOutputRegex('/"err":0/');
        $this->assertLogined(true);

        //test login with name
        $this->dispatch('/login/logout');
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
        $this->assertLogined(false);

        $request->setPost(array(
            'name'=>'wangfeng',
            'password'=>'12345'
        ));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
        $this->assertLogined(true);
    }

}
