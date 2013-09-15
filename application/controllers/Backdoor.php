<?php
/**
 * User: wangfeng
 * Date: 13-7-18
 * Time: 下午10:02
 */
class BackDoorController extends BaseController
{
    public function validateAuth()
    {
        return true;
        return
            AppHelper::getInstance()->isSuperUser($this->userId)||AppHelper::getInstance()->isInternalNet();
    }

    public function sysMsgAction()
    {
        if(!empty($this->userId)){
            $messageModel=MessageModel::getInstance();
            $messageModel->sendSystemMessage($this->userId, 'test system message!');
            echo 'ok';
        }
        return false;
    }

    public function loginAction($uid){
        $userModel=UserModel::getInstance();
        $user=$userModel->fetchOne(array('_id' => intval($uid)));
        $userModel->setLogin($user);
        $this->redirect('/');
        return false;
    }

    public function grantProjectAction($pid)
    {
        $project = ProjectModel::getInstance()->fetchOne(array('_id' => intval($pid)));
        ProjectModel::getInstance()->passProject($project);
        echo "pass project ok";
        return false;
    }

    public function grantUserAction($uid){
        $userModel=UserModel::getInstance();
        $user = $userModel->fetchOne(array('_id' => intval($uid)));
        unset($user['em']);
        unset($user['n']);
        $userModel->passLepei($user);
        echo "pass user ok";
        return false;
    }
}