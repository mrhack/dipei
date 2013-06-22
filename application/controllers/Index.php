<?php
/**
 * @name IndexController
 * @author wangfeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {

	/**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     */
	public function indexAction($name = "Stranger") {
        $viewedLepei = $_COOKIE['_lp'];
		//1. fetch query
		$get = $this->getRequest()->getQuery("get", "default value");

		//2. fetch model
		$model = new SampleModel();

		//3. assign
		$this->getView()->assign("content", 'xxx');
		$this->getView()->assign("name", $name);

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        return TRUE;
	}

    public function index2Action(){
        $get = $this->getRequest()->getParam('test', 'default test');
        echo 'index2 '.$get;
        return false;
    }
}
