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
        $this->assign($this->dataFlow->flow());
//        var_dump($this->getView()->getAssigned());
    }
}