<?php
/**
 * User: wangfeng
 * Date: 13-6-20
 * Time: 下午9:49
 */
class LoginController extends BaseController
{
    public function initLogger()
    {
        $logger=parent::initLogger();
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        return $logger;
    }

    public function loginAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $input=$this->getRequest()->getPost();
            $user=$userModel->login($userModel->format($input, true));
            $session=Yaf_Session::getInstance();
            if($session->has('user')){
                echo "redirect index\n";
                $this->redirect('/index/index');
            }
            if(!empty($user)){
                $session->start();
                $session['user'] = $user;
                $this->render_ajax(Constants::CODE_SUCCESS);
            }else{
                $this->render_ajax(Constants::CODE_LOGIN_FAILED);
            }
            return false;
        }
    }

    public function logoutAction(){
        if($this->getRequest()->isPost()){
            $session=Yaf_Session::getInstance();
            $session->del('user');
            echo "log out\n";
            $this->redirect('/reg/login');
            return false;
        }
    }
}