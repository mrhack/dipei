<?php
/**
 * User: wangfeng
 * Date: 13-6-5
 * Time: 上午12:59
 */
class RegController extends BaseController{

    public function initLogger()
    {
        $logger=parent::initLogger();
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        return $logger;
    }

    public function indexAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $input=$_REQUEST;
            $userModel->createUser($userModel->format($input,true));
            $this->render_ajax(Constants::CODE_SUCCESS);
            return false;
        }else{
           echo "render register\n";
        }
    }


}