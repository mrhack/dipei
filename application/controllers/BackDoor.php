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
        return AppHelper::getInstance()->isInternalNet();
    }

    public function loginAction($uid){
        $userModel=UserModel::getInstance();
        $user=$userModel->fetchOne(array('_id' => intval($uid)));
        $userModel->setLogin($user);
        $this->redirect('/');
        return false;
    }
}