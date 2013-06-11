<?php
/**
 * User: wangfeng
 * Date: 13-6-3
 * Time: 下午9:08
 */
define('TEST_ROOT',__DIR__);
define('ROOT_DIR', __DIR__.'/..');
define('APPLICATION_PATH',ROOT_DIR.'/application');
require_once TEST_ROOT .'/../vendor/autoload.php';

/**
 */
class DipeiTestCase extends  PHPUnit_Framework_TestCase
{
    /**
     * @var Yaf_Request_Simple
     */
    protected $request;

    /**
     * @var Twig_Adapter
     */
    protected $view;

    public static function setUpBeforeClass()
    {
        $yaf=new Yaf_Application(ROOT_DIR.'/conf/application.ini');
        Yaf_Registry::set('config', $yaf->getConfig());
        $yaf->getDispatcher()->returnResponse(true);
        $yaf->getDispatcher()->disableView();
        AppLocal::init(null);
    }

    public function setUp()
    {
        $this->getYaf()->getDispatcher()->setView($this->view);
        $this->view = new Twig_Adapter(APPLICATION_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());
    }

    public function getYaf()
    {
        return Yaf_Application::app();
    }

    public function tearDown()
    {
    }

    /**
     * @param $requestUri uri
     */
    public function dispatch($requestUri)
    {
        $this->request=new Yaf_Request_Simple();
        $this->request->setRequestUri($requestUri);
        return $this->getYaf()->getDispatcher()->dispatch($this->request);
    }

//    public function test2()
//    {
//        require_once __DIR__.'/models/TestModel.php';
//        new TestModel();
//    }

//    public function test1()
//    {
//        $this->dispatch('/index/index/index/name/wangwang/');
//        $assignedVar=$this->view->getAssigned();
//        $this->assertEquals('wangwang', $assignedVar['name']);
//    }
}