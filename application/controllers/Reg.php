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
            $userModel=new UserModel();
           echo "do register\n";
            var_dump($name, $email, $password);
           return false;
        }else{
           echo "render register\n";
            $this->getView()->assign('name', $name);
            $this->getView()->assign('email', $email);
            $this->getView()->assign('password', $password);
        }
    }

}