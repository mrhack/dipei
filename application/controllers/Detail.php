<?php
/**
 * User: wangfeng
 * Date: 13-6-30
 * Time: 下午9:09
 */
class DetailController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'index'){
            $uid = $this->getRequest()->getParam('uid', 0);
            if(!UserModel::getInstance()->isValidId($uid)){
                $this->getLogger()->warn("not found uid $uid", $this->getRequest());
                return false;
            }
        }
        return true;
    }

    public function indexAction($uid)
    {
        $this->assignViewedLepei();
        $this->dataFlow->fuids[] = intval($uid);
        $this->assign(array('VUID' => $uid));
        $data=$this->dataFlow->flow();
        $this->assign($data);

        $_lp = $this->getRequest()->getCookie('_lp', '');
        $viewedLepei = $_lp ? explode(',', $_lp ) : array();
        if(isset($data['USERS'][$uid])
            && (array_search($uid,$viewedLepei) === false)){

            $viewedLepei[]=$uid;
            if(count($viewedLepei) >4){
                $viewedLepei=array_slice($viewedLepei, -4);
            }
            $this->setCookie('_lp', $viewedLepei);
        }
//        var_dump($this->getView()->getAssigned());
    }
}