<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午2:31
 */
class AjaxController extends BaseController
{
    public function locSearchAction($k){
        $k = urldecode($k);
        $locationModel=LocationModel::getInstance();
        $results=$locationModel->searchLocation($k);
        $this->render_ajax(Constants::CODE_SUCCESS, '', $results);
        return false;
    }

    public function translatesAction(){
        $this->getDataFlow()->tids = range(1, 1000);
        $this->render_ajax(Constants::CODE_SUCCESS,'',$this->getDataFlow()->flow());
        return false;
    }
}