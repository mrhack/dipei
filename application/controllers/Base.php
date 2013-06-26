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

    protected  $user;

    public function getDataFlow()
    {
        static $appFlow=null;
        if($appFlow === null){
            $appFlow=new AppDataFlow();
        }
        return $appFlow;
    }

    public function assignBase()
    {
        //TODO check user exists
        if (Yaf_Session::getInstance()->has('user')) {
           $this->user = UserModel::getInstance()->fetchOne(array('_id'=>Yaf_Session::getInstance()['user']['_id']));
           $this->getDataFlow()->users[$this->user['_id']] = UserModel::getInstance()->format($this->user);
           $this->getView()->assign(array('UID'=>$this->user['_id']));
        }
        $this->getDataFlow()->tids = array_merge($this->getDataFlow()->tids,range(1,1000));
    }

    public function assignViewedLepei()
    {
        $viewedLepei=$this->getRequest()->getCookie('_lp');
        if(!empty($viewedLepei)){
            $uids = array_unique(array_map('intval', explode(',', $viewedLepei)));
            $this->getDataFlow()->uids+=$uids;
        }
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

    public function render_ajax($code,$message='',$data=null,$renderPath='')
    {
        if(empty($renderPath)){
            $renderPath = strtolower(sprintf('%s/%s.%s', $this->getRequest()->getControllerName(), $this->getRequest()->getActionName(), Yaf_Application::app()->getConfig()['application']['view']['ext']));
        }
        $html='';
        if(file_exists($this->getViewpath()[0].'/'.$renderPath) && !$this->getRequest()->isPost()){
            $html = $this->getView()->render($renderPath,$data);
        }

        echo json_encode(array(
            'err'=>$code,
            'msg'=>$message,
            'data'=>$data,
            'html'=>$html
        )),"\n";
    }
}