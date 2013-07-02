<?php
/**
 * @name IndexController
 * @author wangfeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

    public function validateAuth()
    {
        return true;//always ok
    }

	public function indexAction() {
        //append viewed lepeis
        $viewedLepeis = explode(',',$this->getRequest()->getCookie('_lp',''));
        $this->dataFlow->uids=array_merge($this->dataFlow->uids,$viewedLepeis);
        //append locids
        $locids=array(4,223,3932,445,556);

        $locationModel=LocationModel::getInstance();
        $this->dataFlow->locations[0]=$locationModel->format($locationModel->getGlobalLocation());
        $this->dataFlow->locations[0]['counts']['country'] = $locationModel->count(array('pt' => array('$size' => 1)));
        $this->dataFlow->lids = array_merge($this->dataFlow->lids, $locids);
        $this->getView()->assign(array('locids' => $locids));
        $this->getView()->assign($this->dataFlow->flow());
//        var_dump($this->getView()->getAssigned());
//        return false;
	}
}
