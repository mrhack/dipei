<?php
/**
 * @name IndexController
 * @author wangfeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

	public function indexAction() {
        $this->assignBase();
        $dataFlow = $this->getDataFlow();
        $search_list=array(1,17,30,423);
        $dataFlow->lids+=$search_list;
        $dataFlow->tids+=Constants::$LEPEI_TYPES;
        $this->getView()->assign(array('search_list'=>$search_list));
        $this->getView()->assign($this->getDataFlow()->flow());
	}
}
