<?php
/**
 * User: wangfeng
 * Date: 13-5-28
 * Time: 下午11:45
 *
 * base:global.js
 *
 * @method Twig_Adapter getView()
 */
class BaseController extends  Yaf_Controller_Abstract
{
    use AppComponent;

    /**
     * @var array
     */
    protected  $user;

    /**
     * @var int
     */
    protected $userId=0;

    /**
     * @var AppDataFlow
     */
    protected $dataFlow;

    /**
     * @var array
     */
    protected $allow;

    /**
     * @var array
     */
    protected $deny;

    public function dump()
    {
        header('Content-Type:text/html;charset=utf8');
        var_dump($this->getView()->getAssigned());
        exit;
    }

    public function isLepei(){
        return UserModel::getInstance()->isLepei($this->user);
    }

    public function init()
    {
        $this->dataFlow=new AppDataFlow();

        if (Yaf_Session::getInstance()->has('user')) {
            $this->user = UserModel::getInstance()->fetchOne(array('_id'=>Yaf_Session::getInstance()['user']['_id']));
            unset($this->user['pw']);
            $this->userId = $this->user['_id'];
            $this->getView()->assign(array('UID'=>$this->user['_id']));
            $this->dataFlow->fuids[]=$this->userId;
            $this->dataFlow->mergeOne('users', $this->user);
            $this->setCookie('UID', $this->user['_id']);
        }

        $this->dataFlow->tids = array_merge($this->dataFlow->tids,range(1,1000));

        $search_list=array(1,17,30,423);
        $this->dataFlow->lids+=$search_list;
        $this->getView()->assign(array('search_list'=>$search_list));

        if(!$this->validateAuth()){
            $action=$this->getRequest()->getActionName();
            $passed=null;
            if(!empty($this->allow)){
                if(is_null($passed)){
                    $passed=false;
                }
                foreach($this->allow as $rule){
                    if(preg_match($rule,$action)){
                        $passed=true;
                        break;
                    }
                }
            }
            if(!empty($this->deny)){
                if(is_null($passed)){
                    $passed=true;
                }
                foreach($this->deny as $rule){
                    if(preg_match($rule,$action)){
                        $passed=false;
                        break;
                    }
                }
            }
            if(!$passed){
                $this->handleInvalidateAuth();
            }
        }
    }

    /**
     * 当权限不被验证的时候，会先过一次白名单黑名单，然后调用handleInvalidateAuth
     * @return bool
     */
    public function validateAuth()
    {
        return !empty($this->user);
    }

    /**
     *
     */
    public function handleInvalidateAuth()
    {
        $this->redirect('/');
    }

    public function assignViewedLepei()
    {
        $viewedLepei=$this->getRequest()->getCookie('_lp');
        if(!empty($viewedLepei)){
            $uids = array_unique(array_map('intval', explode(',', $viewedLepei)));
            $this->dataFlow->fuids=array_merge($this->dataFlow->uids,$uids);
        }
    }

    public function setCookie($name,$val,$expire=null,$path=null)
    {
        if(is_null($expire)){
            $expire=Constants::$COOKIE_EXPIRE;
        }
        if(is_null($path)){
            $path=Constants::$COOKIE_PATH;
        }
        if(is_array($val)){
            $val = join(',', $val);
        }
        setcookie($name, is_string($val)?$val:strval($val), time()+$expire, $path);
    }

    public function wrapInput($method,$args){
        $class = new ReflectionClass(get_class($this));
        $split = strrpos($method, ':');
        if($split !== false){
            $method = substr($method, $split+1);
        }
        $method=$class->getMethod($method);
        $params=$method->getParameters();
        $out=array();
        foreach($params as $param){
            $out[$param->getName()] = $args[$param->getPosition()];
        }
        return $out;
    }

    public function assign($var)
    {
        $this->getView()->assign($var);
    }

    public function render($tpl,array $vars=null){
        if($this->getRequest()->getQuery('debug',false)){
            $this->dump();
        }
        $renderPath=$this->getRenderPath();
        $this->assign(array('TEMPLATE'=>$renderPath));
        $list=Sta::getPageCssList($renderPath);
        $this->assign(array('page_css_list'=>$list));
        return parent::render($tpl, $vars);
    }

    public function getRenderPath()
    {
        $renderPath = strtolower(sprintf('%s/%s.%s', $this->getRequest()->getControllerName(), $this->getRequest()->getActionName(), Yaf_Application::app()->getConfig()['application']['view']['ext']));
        return $renderPath;
    }

    public function render_ajax($code,$message='',$data=null,$renderPath='', $renderData=null)
    {
        if(empty($renderPath)){
            $renderPath = $this->getRenderPath();
        }
        if(empty($message) && isset(GenErrorDesc::$descs[$code])){
            $message = _e(GenErrorDesc::$descs[$code]);
        }
        $html='';
        if(file_exists($this->getViewpath()[0].'/'.$renderPath) && !$this->getRequest()->isPost()){
            $html = $this->getView()->render($renderPath,$renderData);
        }
        echo json_encode(array(
            'err'=>$code,
            'msg'=>$message,
            'data'=>$data,
            'html'=>$html
        )),"\n";
    }

    public function getProjectInfo()
    {
        $projectInfo = ProjectModel::getInstance()->format($this->getRequest()->getRequest(), true);
        foreach($projectInfo['ds'] as $k=>$day){
            $projectInfo['ds'][$k]['dsc']=Json2html::newInstance($projectInfo['ds'][$k]['dsc'])->run();
        }
        $customThemes=$this->getRequest()->getPost('custom_themes');
        if(!empty($customThemes)){
            foreach($customThemes as $custom){
                $tid = TranslationModel::getInstance()->fetchOrSaveCustomWord(array(AppLocal::currentLocal() => $custom));
                $projectInfo['tm'][]=$tid;
            }
        }
        $customServices=$this->getRequest()->getPost('custom_services');
        if(!empty($customServices)){
            foreach($customServices as $custom){
                $tid = TranslationModel::getInstance()->fetchOrSaveCustomWord(array(AppLocal::currentLocal() => $custom));
                $projectInfo['ts'][]=$tid;
            }
        }
        return $projectInfo;
    }

    public function getPostInfo()
    {
        $postInfo = PostModel::getInstance()->format($this->getRequest()->getRequest(), true);
        $postInfo['c']=Json2html::newInstance($postInfo['c'])->run();
        return $postInfo;
    }
}