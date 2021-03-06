<?php
/**
 * User: wangfeng
 * Date: 13-6-5
 * Time: 上午12:59
 */
class RegController extends BaseController{

    public function validateAuth()
    {
        return true;
    }

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
            $input=$this->getRequest()->getPost();
            $userModel->createUser($userModel->format($input,true));
            $this->render_ajax(Constants::CODE_SUCCESS);
        }
        return false;
    }


}