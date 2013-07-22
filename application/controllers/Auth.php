<?php
/**
 * User: wangfeng
 * Date: 13-6-22
 * Time: 下午5:00
 */
class AuthController extends  BaseController
{
    public function indexAction()
    {
        if($this->getRequest()->isPost()){
            $userModel = UserModel::getInstance();
            $user = $this->user;
            if(!isset($user['as'])){
                $userInfo=$userModel->format($this->getRequest()->getRequest(),true);
                $userInfo['as']=1;
            }else{
                $projectInfo=$userModel->format($this->getRequest()->getRequest(),true,'ps');
                foreach($projectInfo['ds'] as $k=>$day){
                    $projectInfo['ds'][$k]['dsc']=Json2html::getInstance($projectInfo['ds'][$k]['dsc'])->run();
                }
                $projectInfo['bp'] = intval(RateModel::getInstance()->convertRate($projectInfo['p'], $projectInfo['pu'])*1000000);
                $userInfo=array('ps'=>array($projectInfo),'as'=>max(2,$user['as']+1));
                $customThemes=$this->getRequest()->getPost('custom_themes');
                if(!empty($customThemes)){
                    foreach($customThemes as $custom){
                        $tid = TranslationModel::getInstance()->fetchOrSaveCustomWord(array(AppLocal::currentLocal() => $custom));
                        $userInfo['ps'][0]['tm'][]=$tid;
                    }
                }
                $customServices=$this->getRequest()->getPost('custom_services');
                if(!empty($customServices)){
                    foreach($customServices as $custom){
                        $tid = TranslationModel::getInstance()->fetchOrSaveCustomWord(array(AppLocal::currentLocal() => $custom));
                        $userInfo['ps'][0]['ts'][]=$tid;
                    }
                }
            }
            $userInfo['_id'] = $this->user['_id'];

            try{
                $userModel->updateUser($userInfo);
                $this->render_ajax(Constants::CODE_SUCCESS);
            }catch(AppException $ex){
                $this->getLogger()->error('save auth failed '.$ex->getMessage(),$userInfo);
                throw $ex;
            }
            return false;
        }else{
            $render=$this->dataFlow->flow();
            $this->getView()->assign($render);
        }
    }
}