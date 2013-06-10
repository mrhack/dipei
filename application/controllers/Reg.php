<?php
/**
 * User: wangfeng
 * Date: 13-6-5
 * Time: 上午12:59
 */
class RegController extends BaseController{

    public function indexAction($name,$email,$password)
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $input=$this->wrapInput(__METHOD__,func_get_args());
            $userModel->createUser($userModel->format($input,true));
            $this->render_ajax(Constants::CODE_SUCCESS);
            return false;
        }else{
           echo "render register\n";
            $this->getView()->assign('name', $name);
            $this->getView()->assign('email', $email);
            $this->getView()->assign('password', $password);
        }
    }

    public function loginAction($email,$password)
    {
        $userModel=UserModel::getInstance();
        $input = $this->wrapInput(__METHOD__, func_get_args());
        $user=$userModel->login($userModel->format($input, true));
        $session=Yaf_Session::getInstance();
        if($session->has('user')){
            $this->getResponse()->setRedirect('/index');
            return false;
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

    public function logoutAction(){
        $session=Yaf_Session::getInstance();
        $session->del('user');
        $this->getResponse()->setRedirect('/index');
        return false;
    }

}