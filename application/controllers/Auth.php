<?php
/**
 * User: wangfeng
 * Date: 13-6-22
 * Time: 下午5:00
 */
class AuthController extends  BaseController
{
    public function indexAction()
    {
        $this->assignBase();
        $dataFlow=$this->getDataFlow();
        $this->getView()->assign($dataFlow->flow());
    }
}