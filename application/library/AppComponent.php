<?php
/**
 * User: wangfeng
 * Date: 13-5-28
 * Time: 下午10:57
 */
class AppComponent
{
    /**
     * @var MonoLog\Logger
     */
    protected $logger;

    public function __construct(){
        $this->getLogger();//init logger
    }

    /**
     * 获取类名。以_分割namespace这种命名规范为准
     * @return string
     */
    public function getRealClassName(){
        static $realClassName;
        if($realClassName !== null) return $realClassName;
        $realClassName=get_class($this);
        $realClassName = str_replace('\\', '_', $realClassName);
        return $realClassName;
    }

    /**
     * @return MonoLog\Logger
     */
    public function getLogger()
    {
        //lazy
        if($this->logger !=null) return $this->logger;

        $suffixes=array('Model','Controller','Plugin','Exception');
        $realClassName=$this->getRealClassName();

        //do revert
        foreach($suffixes as $suffix){
            //check if last,revert suffix
            $pos = stripos($realClassName, $suffix);
            if($pos + strlen($suffix) === strlen($realClassName)){
                $realClassName=$suffix.substr($realClassName,0,$pos);
                break;
            }
        }
        $prefix='';
        preg_match('/[A-Z][a-z]+/', $realClassName, $prefix);
        $logName = strtolower($realClassName);
        if(!empty($prefix)){
            $prefix = array_shift($prefix);
            $logName = strtolower($prefix) . '.' . substr($logName, strlen($prefix));
        }
        if($logName[strlen($logName)-1] == '_'){
            $logName = substr($logName, 0, strlen($logName) - 1);
        }
        $logName .= date('Ymd');
        $logPath=Constants::PATH_LOG.'/'.$logName;

        $this->logger = AppLogger::newLogger($realClassName, $logPath);
        return $this->logger;
    }
}