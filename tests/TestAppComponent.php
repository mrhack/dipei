<?php
/**
 * User: wangfeng
 * Date: 13-6-4
 * Time: 下午11:23
 */
require_once __DIR__ . '/DipeiTestCase.php';
require_once APPLICATION_PATH . '/library/AppComponent.php';

class TestModel
{
    use AppComponent;

}

class TestController
{
    use AppComponent;

}

class TestPlugin
{
    use AppComponent;
}

class Test_Other_Class
{
    use AppComponent;
}

class TestAppComponent extends DipeiTestCase
{
    public function testLogger()
    {
        $expectedModelLog = Constants::PATH_LOG . '/model.test.' . date('Ymd');
        $expectedControllerLog = Constants::PATH_LOG . '/controller.test.' . date('Ymd');
        $expectedPluginLog = Constants::PATH_LOG . '/plugin.test.' . date('Ymd');
        $expectedOtherLog = Constants::PATH_LOG . '/test.other_class.' . date('Ymd');

        foreach(array('model.test','controller.test','plugin.test','test.other_class') as $cleanName){
            foreach(glob(Constants::PATH_LOG."/$cleanName.*") as $file){
                echo "unlink $file\n";
                unlink($file);
            }
        }

        $testModel=new TestModel();
        $testModel->getLogger()->info('hello model!',array('name'=>'wangfeng','_id'=>123));
        $this->assertFileExists($expectedModelLog);

        $testController=new TestController();
        $testController->getLogger()->info('hello controller!');
        $this->assertFileExists($expectedControllerLog);

        $testPlugin=new TestPlugin();
        $testPlugin->getLogger()->info('hello plugin!');
        $this->assertFileExists($expectedPluginLog);

        $testOtherClass=new Test_Other_Class();
        $testOtherClass->getLogger()->info('hello other_class!');
        $this->assertFileExists($expectedOtherLog);

    }
}
