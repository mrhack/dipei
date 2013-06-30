<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:00
 */
class ProfileController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->assign($this->dataFlow->flow());
//        var_dump($this->getView()->getAssigned());return false;
    }

    public function settingAction()
    {

    }
}