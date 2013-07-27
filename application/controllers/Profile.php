<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:00
 */
class ProfileController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'index'){
            $type = $this->getRequest()->getParam('type', 'guest');
            if($type=='host' && !$this->isLepei()){
                return false;
            }
        }
        return parent::validateAuth();
    }

    public function indexAction($type,$module)
    {
        $type = strtolower($type);
        $this->assign(array('TYPE'=>$type,'MODULE'=>$module));
        if($module == 'wish-users'){
            $likes=LikeModel::getInstance()->fetch(array('uid'=>$this->userId,'tp'=>Constants::LIKE_USER));
            $uids=array();
            foreach($likes  as $l){
                $uids[] = $l['oid'];
            }
            $this->assign(array('wish_users'=>$uids));
            $this->dataFlow->fuids = array_merge($this->dataFlow->fuids, $uids);
            //
        }else if($module == 'wish-location'){
            $likes=LikeModel::getInstance()->fetch(array('uid'=>$this->userId,'tp'=>Constants::LIKE_LOCATION));
            $lids=array();
            foreach($likes as $l){
                $lids[] = $l['oid'];
            }
            $this->assign(array('wish_locations'=>$lids));
            $this->dataFlow->lids = array_merge($this->dataFlow->lids, $lids);
        }
        $this->getView()->assign($this->dataFlow->flow());
    }

    public function removeProjectAction()
    {
        $pid=$this->getRequest()->getPost('pid',0);
        $projectModel=ProjectModel::getInstance();
        $project=$projectModel->fetchOne(array('_id' => $pid, 'uid' => $this->userId));
        if(empty($project)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        $projectModel->removeProject($project);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function addProjectAction()
    {
        $projectModel=ProjectModel::getInstance();
        $projectInfo = $this->getProjectInfo();
        if(empty($projectInfo)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        $projectModel->addProject($projectInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function updateProjectAction()
    {
        $projectModel=ProjectModel::getInstance();
        $projectInfo = $this->getProjectInfo();
        $projectModel->updateProject($projectInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }


    public function settingAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $userInfo = $userModel->format($this->getRequest()->getRequest(), true);
            $userInfo['_id']=$this->userId;
            $userModel->updateUser($userInfo);
        }
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}