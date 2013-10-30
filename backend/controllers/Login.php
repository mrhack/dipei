<?php
/**
 * User: wangfeng
 * Date: 13-10-29
 * Time: 下午9:06
 */
class LoginController extends BaseBackEndController
{
    static $USERS=array(
        array(
            'name'=>'wangfeng',
            'pwd'=>'whfg@xianlvke'
        ),
        array(
            'name'=>'huangdegang',
            'pwd'=>'hldegh@xianlvke'
        ),
        array(
            'name'=>'hujie',
            'pwd'=>'hujp@xianlvke'
        ),
        array(
            'name'=>'yihaitao',
            'pwd'=>'yihdtc@xianlvke'
        )
    );

    public function validateAuth()
    {
        return true;
    }

    public function indexAction()
    {
        if(Yaf_Session::getInstance()->has('backendUser')){
            $this->redirect('/backend/');
            return false;
        }
        if($this->getRequest()->isPost()){
            $user = $this->getRequest()->getRequest()['User'];

            $backUser = self::$USERS[$user['name']];
            if(empty($backUser) || $backUser['pwd'] !== $user['password']){
                $this->assign(array('errorMessage'=>'您输入的账号或密码不正确,请重新填写'));
            }else{
                Yaf_Session::getInstance()->start();
                Yaf_Session::getInstance()->set('backendUser', $backUser);
                $this->redirect('/backend/');
                return false;
            }
        }
    }

    public function logoutAction()
    {
        Yaf_Session::getInstance()->del('backendUser');
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}

foreach(LoginController::$USERS as $user){
    LoginController::$USERS[$user['name']]=$user;
}
