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
        $this->dataFlow->fuids[] = intval($uid);
        $this->assign(array('VUID' => $uid));
        $data=$this->dataFlow->flow();
        $this->assign($data);

        $viewedLepei = explode(',', $this->getRequest()->getCookie('_lp', ''));
        if(isset($data['USERS'][$uid])
            && (array_search($uid,$viewedLepei) === false)){
            $viewedLepei[]=$uid;
            $this->setCookie('_lp', $viewedLepei);
        }
//        var_dump($this->getView()->getAssigned());
    }
}