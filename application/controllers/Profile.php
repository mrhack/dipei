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