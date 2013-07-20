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
        if($module == 'wish_user'){
            $likes=LikeModel::getInstance()->fetch(array('uid'=>$this->userId,'tp'=>Constants::LIKE_USER));
            $uids=array();
            foreach($likes  as $l){
                $uids[] = $l['oid'];
            }
            $this->assign(array('wish_users'=>$uids));
            $this->dataFlow->fuids = array_merge($this->dataFlow->fuids, $uids);
            //
        }else if($module == 'wish_location'){
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
        $userModel=UserModel::getInstance();
        $userModel->removeProject($this->user, $pid);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function addProjectAction()
    {
        $userModel=UserModel::getInstance();
        $projectInfo=$userModel->format($this->getRequest()->getPost('project', array()),true,'ps');
        $userModel->addProject($this->user, $projectInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function updateProjectAction()
    {
        $userModel=UserModel::getInstance();
        $projectInfo = $userModel->format($this->getRequest()->getPost('project', array()), true, 'ps');
        $userModel->updateProject($this->user, $projectInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }


    public function settingAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $userInfo = $userModel->format($this->getRequest()->getRequest(), true);
            $userModel->updateUser($userInfo);
        }
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}