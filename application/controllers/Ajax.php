<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午2:31
 */
class AjaxController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'like' || $this->getRequest()->getActionName() == 'unlike'){
            if(empty($this->user)){
                throw new AppException(Constants::CODE_NO_PERM);
            }
        }
        return true;//always ok
    }

    public function locSearchAction($k){
        $k = urldecode($k);
        $locationModel=LocationModel::getInstance();
        $results=$locationModel->searchLocation($k);
        $this->render_ajax(Constants::CODE_SUCCESS, '', $results);
        return false;
    }

    public function citySearchAction($k){
        $k = urldecode($k);
        $locationModel=LocationModel::getInstance();
        $results=$locationModel->searchLocation($k,30,2,2);
        $this->render_ajax(Constants::CODE_SUCCESS, '', $results);
        return false;
    }

    public function countrySearchAction($k){
        $k = urldecode($k);
        $locationModel=LocationModel::getInstance();
        $results=$locationModel->searchCountry($k);
        $this->render_ajax(Constants::CODE_SUCCESS, '', $results);
        return false;
    }

    public function hasLocationAction(){
        $name = urldecode($this->getRequest()->getRequest('n' , ''));
        $city = $this->getRequest()->getRequest('city' , 0);
        $loc = LocationModel::getInstance()->fetchOne( array('n'=>$name) );
        if( empty( $loc ) ){
            throw new AppException(Constants::CODE_LOCATION_NOT_FOUND);
        } else if( !empty( $city ) && count( $loc['pt'] ) < 2 ){
            throw new AppException(Constants::CODE_LOCATION_MUST_CITY);
        } else {
            $this->render_ajax(Constants::CODE_SUCCESS , '' , $loc);
        }
        return false;
    }

    public function translatesAction(){
        $this->dataFlow->tids = range(1, 1000);
        $this->render_ajax(Constants::CODE_SUCCESS,'',$this->dataFlow->flow());
        return false;
    }

    public function validateAction()
    {
        $model=$this->getRequest()->getRequest('model');
        $field=$this->getRequest()->getRequest('field');
        $value=$this->getRequest()->getRequest('value');
        $context=$this->getRequest()->getRequest('context');

        $modelClass = ucfirst($model) . 'Model';
        if(!class_exists($modelClass)){
            throw new AppException(Constants::CODE_PARAM_INVALID,'not found model class');
        }
        $model=new $modelClass();

        $fieldHierarcy = explode('.', $field);
        $data=array();
        $tmp=&$data;
        foreach($fieldHierarcy as $f)
        {
            $tmp =& $tmp[$f];
        }
        $tmp=$value;
        unset($tmp);
        $data = $model->format($data, true);
        $model->validate($data,$context);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function likeAction()
    {
        $type=$this->getRequest()->getRequest('tp');
        if( empty( $type ) ){
            throw new AppException(Constants::CODE_NOT_FOUND_LIKE_OBJECT);
        }
        $objectId = $this->getRequest()->getRequest('oid', 0);
        if( $type == Constants::LIKE_USER && $objectId == $this->userId ){
            throw new AppException(Constants::CODE_INVALID_LIKE_ID);
        }
        $likeModel=LikeModel::getInstance();
        $likeModel->like($this->userId, $type,$objectId);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function unlikeAction()
    {
        $type=$this->getRequest()->getRequest('tp');
        $objectId = $this->getRequest()->getRequest('oid', 0);
        LikeModel::getInstance()->unlike($this->userId,$type,$objectId);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}