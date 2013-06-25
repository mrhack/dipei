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
            $userInfo=$lepeiTempModel->format($this->getRequest()->getRequest(),true);
            $userInfo['_id'] = 5;
//            $customLanguages=$this->getRequest()->getPost('custom_languages');
//            if(!empty($customLanguages)){
//                foreach($customLanguages as $custom=>$familar){
//                    $tid=TranslationModel::getInstance()->fetchOrSaveCustomWord(array(AppLocal::currentLocal() => $custom));
//                    $userInfo['ls'][$tid]=$familar;
//                }
//            }
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
            //FIXME Need strong validation?
            if(isset($userInfo['as'])){
                if($userInfo['as']<3){
                    $userInfo['as']++;
                }
            }else{
                $userInfo['as']=1;
            }
            try{
                $ret=$lepeiTempModel->update($userInfo,null,array('upsert'=>true));
                $this->render_ajax(Constants::CODE_SUCCESS);
            }catch(AppException $ex){
                $this->getLogger()->error('save auth failed '.$ex->getMessage(),$userInfo);
                $this->render_ajax($ex->getCode(),$ex->getMessage());
            }
            return false;
        }else{
            $this->assignBase();
            $dataFlow=$this->getDataFlow();
            $render=$dataFlow->flow();
            $tempUser=LepeiTempModel::getInstance()->fetchOne(array('_id'=>$this->user['_id']));
            $render['step'] = isset($tempUser['as'])?$tempUser['as']+1:1;
            $this->getView()->assign($render);
//            var_dump($this->getView()->getAssigned());
        }
    }
}