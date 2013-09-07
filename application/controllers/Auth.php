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
        if($this->getRequest()->isPost()){
            $userModel = UserModel::getInstance();
            $user = $this->user;
            if(!isset($user['as'])){
                $userInfo=$userModel->format($this->getRequest()->getRequest(),true);
                $userInfo['as']=1;
            }else{
                $projectModel=ProjectModel::getInstance();
                $projectInfo = $this->getProjectInfo();
                $projectInfo['uid']=$this->userId;
                $projectInfo['s']=Constants::STATUS_NEW;
                $projectModel->addProject($projectInfo);
                $userInfo=array('as'=>max(2,$user['as']+1));
            }
            $userInfo['_id'] = $this->user['_id'];

            try{
                $userModel->updateUser($userInfo);
                $this->render_ajax(Constants::CODE_SUCCESS);
            }catch(AppException $ex){
                $this->getLogger()->error('save auth failed '.$ex->getMessage(),$userInfo);
                throw $ex;
            }
            return false;
        }else{
            $render=$this->dataFlow->flow();
            $this->getView()->assign($render);
        }
    }
}