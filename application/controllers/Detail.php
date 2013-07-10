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
        return true;
    }

    public function indexAction($uid)
    {
        if(!UserModel::getInstance()->isValidId($uid)){
            exit;
            $this->handleInvalidateAuth();
            return false;
        }
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