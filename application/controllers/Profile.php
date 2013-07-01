<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:00
 */
class ProfileController extends BaseController
{
    public function indexAction()
    {
        $this->getView()->assign($this->dataFlow->flow());
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