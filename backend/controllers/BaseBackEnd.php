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
class BaseBackEndController extends  Yaf_Controller_Abstract
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

   
    public function getPage() {
        return max(1,(int)$this->getRequest()->getRequest('page',1));
    }

    public function getPagination($page,$pageSize,$count){
        $pageSize = max(1, $pageSize);
        $page = max(1, $page);
        $pageCount=(int)ceil($count/$pageSize);
        return array(
            'pagination'=>array(
                'current'=>$page,
                'total'=>$pageCount,
                'count'=>$count
            )
        );
    }

    public function getDipeiTerm($tid){
        return $this->getDipeiTerms()[$tid];
    }

    const LOC_FORMAT_FULL_CITY=1;
    const LOC_FORMAT_CITY=2;
    const LOC_FORMAT_COUNTRY=3;

    public function getLocationString($lid,$format){
        if(empty($lid)){
            return '';
        }
        $loc=LocationModel::getInstance()->fetchOne(array('_id' => intval($lid)));
        $str = '';
        switch($format){
            case self::LOC_FORMAT_FULL_CITY:
                if($loc['pt']>1){
                    $parent=LocationModel::getInstance()->fetchOne(array('_id'=>$loc['pt'][1]));
                }else{
                    $parent=$loc;
                }
                $str= sprintf('%s,%s',$loc['n'],$parent['n']);
                break;
            case self::LOC_FORMAT_CITY:
                $str=sprintf('<a href="http://www.xianlvke.com/loc/city/%s" target="_blank">%s</a>',$lid,$loc['n']);
                break;
            case self::LOC_FORMAT_COUNTRY:
                $str=sprintf('<a href="http://www.xianlvke.com/loc/%s" target="_blank">%s</a>',$lid,$loc['n']);
                break;
        }
        return $str;
    }

    public function getDipeiTerms()
    {
        static $terms=null;
        if($terms === null){
            $terms=array();
            $translations=TranslationModel::getInstance()->fetch(array('_id'=>array('$lte'=>1000)));
            foreach($translations as $id=>$translation){
                $terms[$id]=$translation['zh_CN'];
            }
        }
        return $terms;
    }

    public function translateLocation($lid)
    {
        $location = LocationModel::getInstance()->fetchOne(array('_id' => $lid));
        if($location){
            $translateRecord=$this->doTranslation($location['_id']+1000,$location['n']);
            if(!isset($location['nid'])){
                $location['nid'] = $translateRecord['_id'];
                LocationModel::getInstance()->update($location);
            }
        }
    }

    public function doTranslation($id,$word){

        $translateTasks=array(Constants::LANG_EN,Constants::LANG_PY);
        $translationModel=TranslationModel::getInstance();
        $translator=AppTranslator::getInstance();

        $translateRecord = $translationModel->fetchOne(array('_id'=>$id));
        $translateRecord = $translateRecord?$translateRecord:array('_id'=>$id,Constants::LANG_ZH_CN=>$word);

        $needUpdate=false;
        $changed=($translateRecord[Constants::LANG_ZH_CN] !== $word);
        $translateRecord[Constants::LANG_ZH_CN]=$word;
        foreach($translateTasks as $lang){
            if(!isset($translateRecord[$lang]) || $changed){
                $translateRecord[$lang] = $translator->translate(Constants::LANG_ZH_CN, $lang, $word);
                $needUpdate=true;
            }
        }
        if($needUpdate){
            $translationModel->save($translateRecord);
            $translateRecord = $translationModel->fetchOne(array(Constants::LANG_ZH_CN=>$word));
        }
        return $translateRecord;
    }

    const QUERY_TYPE_INT='int';
    const QUERY_TYPE_TIME = 'time';
    const QUERY_TYPE_ARRAY = 'array';

    /**
     * @param $name
     * @param $type string one of int|time\array
     * @param null $modifier string like $lt/$gt
     * @param null $defaultVal
     * @return array
     */
    public function getMongoQuery($name,$type,$key,$modifier=null,$defaultVal=null)
    {
        $val=$this->getRequest()->getQuery($name,$defaultVal);
        if(empty($val)){
            return array();
        }
        switch($type){
            case 'int':
                $val = intval($val);
                break;
            case 'time':
                $val = new MongoDate(strtotime($val));
                break;
            case 'array':
                $val = explode(',', $val);
                $modifier = '$in';
                break;
        }
        if(!empty($modifier)){
            return array($key=>array($modifier=>$val));
        }else{
            return array($key=>$val);
        }
    }

    public function getMongoBetweenQuery($preName,$key,$queryType)
    {
        $query = array_merge(
            $this->getMongoQuery($preName . 'Start', $queryType, $key,'$gte'),
            $this->getMongoQuery($preName . 'End',$queryType,$key,'$lte')
        );
        return $query;
    }

    public function getTimeBetweenMongoQuery($preName,$key)
    {
        return $this->getMongoBetweenQuery($preName, $key, self::QUERY_TYPE_TIME);
    }

    public function getUserInfoFromQuery($userInput)
    {
        $userQuery['$or'][]=array(
            '_id'=>intval($userInput)
        );
        $userQuery['$or'][]=array(
            'em'=>new MongoRegex("/$userInput/i")
        );
        $userQuery['$or'][]=array(
            'n'=>new MongoRegex("/$userInput/i")
        );
        $userInput = UserModel::getInstance()->fetchOne($userQuery);
        return $userInput;
    }

    public function init()
    {
//        var_dump($this->getRequest());exit;
        if(Yaf_Session::getInstance()->has('backendUser')){
            $this->user = Yaf_Session::getInstance()->get('backendUser');
        }
        $this->dataFlow=new AppDataFlow();

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
        $this->redirect('/backend/login');
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
        if($this->getRequest()->getQuery('debug',false)){
            $this->dump();
        }
        
        if(empty($renderPath)){
            $renderPath = $this->getRenderPath();
        }
        if(empty($message) && isset(GenErrorDesc::$descs[$code])){
            $message = _e(GenErrorDesc::$descs[$code]);
        }
        $html='';
        if(file_exists($this->getViewpath()[0].'/'.$renderPath) /* && !$this->getRequest()->isPost() */){
            $html = $this->getView()->render($renderPath,$renderData);
        }
        echo json_encode(array(
            'err'=>$code,
            'msg'=>$message,
            'data'=>$data,
            'html'=>$html
        )),"\n";
    }
}