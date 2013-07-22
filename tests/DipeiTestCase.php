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
require_once 'DataSet.php';
error_reporting(E_ALL ^ E_NOTICE);

class Test_Http_Request extends Yaf_Request_Http
{
    public $post=array();
    public $req=array();

    public function setPost($p)
    {
        $this->post=$p;
    }

    public function setRequest($g)
    {
        $this->reg=$g;
    }

    public function getPost($v=null,$default=null)
    {
        if(is_null($v) && is_null($default)){
            return $this->post;
        }
        return isset($this->post[$v])?$this->post[$v]:$default;
    }

    public function getRequest($v=null,$default=null)
    {
        $this->req = array_merge($this->req, $this->post);
        if(is_null($v) && is_null($default)){
            return $this->req;
        }
        return isset($this->req[$v])?$this->req[$v]:$default;

    }
}
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

    /**
     * @var DataSet
     */
    protected  $dataSet;

    public function getMockModelNameList()
    {
        return array();
    }

    public static function setUpBeforeClass()
    {
        $yaf=new Yaf_Application(ROOT_DIR.'/conf/application.ini');
        Yaf_Registry::set('config', $yaf->getConfig());
        $yaf->getDispatcher()->returnResponse(true);
        $yaf->getDispatcher()->disableView();
        AppLocal::init(null);
        //build db
        Constants::$DB_LEPEI = 'lepei_test';
        AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectDB(Constants::$DB_LEPEI)->drop();
    }

    /**
     * @deprecated just mock change lepei db
     * @param $modelClassName
     * @return BaseModel
     */
    public function getMockModel($modelClassName)
    {
        static $mockModels=null;
        if(empty($mockModels)){
            $mockModels=array();
        }
        if(isset($mockModels[$modelClassName])){
            return $mockModels[$modelClassName];
        }
        $mockModel = $this->getMock($modelClassName, array('getCollectionName','getAllocatorCollectionName'));
        $mockModel->expects($this->any())->method('getCollectionName')->will($this->returnValue('mock_'.strtolower($modelClassName)));
        $mockModel->expects($this->any())->method('getAllocatorCollectionName')->will($this->returnValue('mock_id_allocator'));
        $reflectClass = new ReflectionClass($modelClassName);
        $reflectClass->setStaticPropertyValue('_instance', $mockModel);
        $this->assertSame($reflectClass->getStaticPropertyValue('_instance'), $mockModel);
        return $mockModel;
    }

    /**
     * @param AppComponent $appCompoent
     */
    public function closeLogger($appCompoent)
    {
        try{
            while($appCompoent->getLogger()->popHandler());
        }catch (Exception $ex){
            ;
        }
    }

    public function setUp()
    {
        $this->getYaf()->getDispatcher()->setView($this->view);
        $this->view = new Twig_Adapter(APPLICATION_PATH.'/views', Yaf_Registry::get("config")->get("twig")->toArray());

        //mock logger
        $mockLogger=$this->getMock('AppLogger');
        $logger=new \Monolog\Logger(get_class($this));
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));
        $logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());
        $logger->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
        $mockLogger->expects($this->any())->method('newLogger')->will($this->returnValue($logger));
        AppLogger::$_instance=$mockLogger;

        $this->dataSet=new DataSet();
    }

    public function tearDown()
    {
    }

    public static function tearDownAfterClass()
    {
        AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectDB(Constants::$DB_LEPEI)->drop();
    }

    public function getYaf()
    {
        return Yaf_Application::app();
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

    public function assertLogined($expectLogined)
    {
        $this->assertEquals($expectLogined,Yaf_Session::getInstance()->has('user'));
    }

    public function assertAjaxCode($errorCode=Constants::CODE_SUCCESS)
    {
        $this->expectOutputRegex('/"err":' . $errorCode . '/');
    }

    /**
     * @param $expect
     * @param $actual
     * @param string $errMsg
     */
    public function assertArrayEquals($expect,$actual,$errMsg='')
    {
        if(is_array($expect)){
            $this->assertTrue(is_array($actual));
            foreach($expect as $k=>$v){
                $this->assertArrayEquals($expect[$k], $actual[$k]);
            }
        }else{
            $this->assertEquals($expect,$actual);
        }
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