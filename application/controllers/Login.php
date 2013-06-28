<?php
/**
 * User: wangfeng
 * Date: 13-6-20
 * Time: 下午9:49
 */
class LoginController extends BaseController
{
    public function init()
    {
        $this->allow=array('/.*/');
        parent::init();
    }

    public function initLogger()
    {
        $logger=parent::initLogger();
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        return $logger;
    }

    public function logoutAction()
    {
        Yaf_Session::getInstance()->del('user');
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function indexAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $input=$this->getRequest()->getPost();
            $user=$userModel->login($userModel->format($input, true));
            if(!empty($user)){
                $this->render_ajax(Constants::CODE_SUCCESS);
            }else{
                $this->render_ajax(Constants::CODE_LOGIN_FAILED);
            }
        }else{
            $this->render_ajax(Constants::CODE_SUCCESS);
        }
        return false;
    }
}