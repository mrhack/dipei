<?php
/**
 * User: wangfeng
 * Date: 13-7-13
 * Time: 下午5:32
 */
class StaController extends BaseController
{
    public function validateAuth()
    {
        return true;
    }

    public function indexAction()
    {
        $this->assign($this->dataFlow->flow());
    }

    public function findPasswordAction(){
    	$this->assign($this->dataFlow->flow());	
    }

    public function resetPasswordAction(){
    	$this->assign($this->dataFlow->flow());	
    }
}