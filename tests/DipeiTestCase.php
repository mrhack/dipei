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
error_reporting(E_ALL ^ E_NOTICE);

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

        //build db
        Constants::$DB_LEPEI = 'lepei_test';
        AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectDB(Constants::$DB_LEPEI)->drop();
    }

    public function tearDown()
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