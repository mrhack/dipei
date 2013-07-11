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
            $tempUser = $lepeiTempModel->fetchOne(array('_id' => $this->user['_id']));
            if(empty($tempUser)){
                $userInfo=$lepeiTempModel->format($this->getRequest()->getRequest(),true);
                $userInfo['as']=1;
            }else{
                $projectInfo=$lepeiTempModel->format($this->getRequest()->getRequest(),true,'ps');
                foreach($projectInfo['ds'] as $k=>$day){
                    $projectInfo['ds'][$k]['dsc']=Json2html::getInstance($projectInfo['ds'][$k]['dsc'])->run();
                }
                $projectInfo['bp'] = intval(RateModel::getInstance()->convertRate($projectInfo['p'], $projectInfo['pu'])*1000000);
                $userInfo=array('ps'=>array($projectInfo),'as'=>max(2,$tempUser['as']+1));
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
                $lepeiTempModel->update($userInfo,null,array('upsert'=>true));
                $this->render_ajax(Constants::CODE_SUCCESS);
            }catch(AppException $ex){
                $this->getLogger()->error('save auth failed '.$ex->getMessage(),$userInfo);
                $this->render_ajax($ex->getCode(),$ex->getMessage());
            }
            return false;
        }else{
            $render=$this->dataFlow->flow();
            $tempUser=LepeiTempModel::getInstance()->fetchOne(array('_id'=>$this->user['_id']));
            $this->getView()->assign($render);
        }
    }
}