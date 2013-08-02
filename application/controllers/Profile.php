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

    private function getLikeOids($type)
    {
        $likes = LikeModel::getInstance()->fetch(
            MongoQueryBuilder::newQuery()->query(array('uid'=>$this->userId,'tp'=>$type))->sort(array('t'=>-1))->build()
        );
        $oids=array();
        foreach($likes as $like){
            $oids[] = $like['oid'];
        }
        return $oids;
    }

    public function indexAction($type,$module)
    {
        $type = strtolower($type);
        $this->assign(array('TYPE'=>$type,'MODULE'=>$module));
        if($module == 'wish-users'){
            $uids=$this->getLikeOids(Constants::LIKE_USER);
            $this->assign(array('wish_users'=>$uids));
            $this->dataFlow->fuids = array_merge($this->dataFlow->fuids, $uids);
            //
        }else if($module == 'wish-location'){
            $lids = $this->getLikeOids(Constants::LIKE_LOCATION);
            $this->assign(array('wish_locations'=>$lids));
            $this->dataFlow->lids = array_merge($this->dataFlow->lids, $lids);
        }else if($module == 'wish-project'){
            $pids = $this->getLikeOids(Constants::LIKE_POST);
            $this->assign(array('wish_projects' => $pids));
            $this->dataFlow->pids = array_merge($this->dataFlow->pids, $pids);
        }
        $this->getView()->assign($this->dataFlow->flow());
    }

    public function removeProjectAction()
    {
        $pid=intval($this->getRequest()->getPost('pid',0));
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
        $projectInfo['uid']=$this->userId;
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
            unset($userInfo['n']);
            $userInfo['_id']=$this->userId;
            $userModel->updateUser($userInfo);
        }
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}