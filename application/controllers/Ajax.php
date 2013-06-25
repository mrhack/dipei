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
        $results=$locationModel->formats(
            $locationModel->searchLocation($k)
        );
        $this->render_ajax(Constants::CODE_SUCCESS, '', $results);
        return false;
    }
}