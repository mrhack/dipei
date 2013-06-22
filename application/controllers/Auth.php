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
            $lepeiTempModel = LepeiTempModel::getInstance();
            $translationModel=TranslationModel::getInstance();
            $userInfo=$lepeiTempModel->format($_REQUEST,true);
            $userInfo['_id'] = $this->user['_id'];
            $customLanguages=$this->getRequest()->getPost('custom_languages');
            if(!empty($customLanguages)){
                foreach($customLanguages as $custom=>$familar){
                    $translate=$translationModel->fetchOne(array(AppLocal::currentLocal() => $custom));
                    if(empty($translate)){
                        $tid=TranslationModel::getInstance()->saveCustomWord(array(AppLocal::currentLocal() => $custom));
                        $userInfo['ls'][$tid]=$familar;
                    }
                }
            }
            if(isset($userInfo['as'])){
                $userInfo['as']++;
            }else{
                $userInfo['as']=1;
            }
            try{
                $lepeiTempModel->update($userInfo);
                $this->render_ajax(Constants::CODE_SUCCESS);
            }catch(AppException $ex){
                $this->getLogger()->error('save auth failed '.$ex->getMessage(),$userInfo);
                $this->render_ajax($ex->getCode(),$ex->getMessage());
            }
        }else{
            $this->assignBase();
            $dataFlow=$this->getDataFlow();
            $this->getView()->assign($dataFlow->flow());

        }
    }
}