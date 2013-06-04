<?php
/**
 * User: wangfeng
 * Date: 13-6-5
 * Time: 上午12:59
 */

class RegController extends BaseController{

    public function indexAction($name,$email,$password)
    {
        var_dump($name, $email, $password);
        if($this->getRequest()->isPost()){
           echo "do register\n";
           return false;
        }else{
           echo "render register\n";
        }
    }

}