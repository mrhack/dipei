<?php
/**
 * User: wangfeng
 * Date: 13-3-22
 * Time: 下午5:10
 */
error_reporting(E_ERROR | E_WARNING);
error_reporting(E_ERROR);
ini_set('max_execution_time', '0');
ini_set('memory_limit', '512M');
ob_end_clean();

define('GLOBAL_SCRIPT', '__script');

$__startArgv=$argv;
$__startArgc=$argc;

class ArgValidator
{
    const TYPE_BOOL = 'bool';
    const TYPE_INT = 'int';

    private $regexp;
    private $type;

    private $min;
    private $max;
    private $in = array();
    private $checkFileExists=false;
    private $checkDir=false;

    private $errorMsg;

    public static function newInstance()
    {
        return new ArgValidator();
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    public function setRegExp($exp)
    {
        $this->regexp = $exp;
        return $this;
    }

    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    public function setCheckFileExists()
    {
        $this->checkFileExists=true;
        return $this;
    }

    public function setCheckDir()
    {
        $this->checkDir=true;
        return $this;
    }

    public function setIn($in)
    {
        $this->in = $in;
        return $this;
    }

//    /**
//     * 参数检验失败时是否直接使用默认值
//     */
//    public function setUseDefault()
//    {
//
//    }

    /**
     * const 中的一种type
     * @param $type
     */
    public function setVarType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function castType($val)
    {
        if (!empty($this->type)) {
            switch ($this->type) {
                case self::TYPE_BOOL:
                    return (bool)$val;
                case self::TYPE_INT:
                    return (int)$val;
            }
        }
        return $val;
    }

    public function validate($val)
    {
        if (is_null($val)) {
            $this->errorMsg = " can't be null!";
            return false;
        }

        if (!empty($this->regexp)) {
            if (!preg_match($this->regexp, $val)) {
                $this->errorMsg = "$val is not validate for {$this->regexp} ";
                return false;
            }
        }
        if (isset($this->min)) {
            if ($val < $this->min) {
                $this->errorMsg = "$val should greater than {$this->min}";
                return false;
            }
        }
        if (isset($this->max)) {
            if ($val > $this->max) {
                $this->errorMsg = "$val should less than {$this->max}";
                return false;
            }
        }
        if (!empty($this->in)) {
            if (false === array_search($val, $this->in)) {
                $this->errorMsg = "$val should in " . join(',', $this->in);
                return false;
            }
        }
        if($this->checkFileExists){
            if(!file_exists($val)){
                $this->errorMsg = "$val not exists!";
                return false;
            }
        }

        if($this->checkDir){
            if(!is_dir($val))  {
                $this->errorMsg = "$val is not dir!";
                return false;
            }
        }

        return true;
    }
}

function getArgMap()
{
    if (isset($GLOBALS[GLOBAL_SCRIPT]['args'])) {
        $argMap = $GLOBALS[GLOBAL_SCRIPT]['args'];
    } else {
        $lastArg = '';
        while (false !== ($arg = shiftArgs())) {
            $arg = mb_strtolower(trim($arg));
            if ($arg == '') {
                continue;
            }
            if ($arg[0] == '-' && !is_numeric($arg)) {
                $arg = substr($arg, 1);
                $argMap[$arg] = true;
                $lastArg = $arg;
            } else {
                if (!empty($lastArg)) {
                    $argMap[$lastArg] = $arg;
                }
            }
        }
        $GLOBALS[GLOBAL_SCRIPT]['args'] = $argMap;
    }
    return $argMap;
}

//TODO 增加argv的功能,增加multiargValue
function getArgValue($argName, $default, $helpMsg = '', $validator = null)
{
    $argMap = getArgMap();
    //ensure help message
    if (isset($argMap['h'])) {
        echo "[USEAGE]:\n", $helpMsg;
        exit;
    }

    $argVal = @$argMap[$argName];
    if ($argVal === null || $argVal === '') {
        $argVal = $default;
    }
    if ($validator instanceof ArgValidator) {
        $argVal = $validator->castType($argVal);
        if (!$validator->validate($argVal)) {
            echo "[ERROR]:\n", $argName . "-" . $validator->getErrorMsg(), "\n";
            echo "[USEAGE]:\n", $helpMsg;
            exit;
        }
    }
    return $argVal;
}

function shiftArgs()
{
    global $argc, $argv;
    //skip self file name arg
    $argc--;
    if ($argc >= 0)
        return array_shift($argv);
    else
        return false;
}

/**
 *
 * @return array|mixed
 */
function getMongoConditionFromArg($argName, $default = array())
{
    $tmp = getArgValue($argName,null);
    if(is_null($tmp)){
        $condition=$default;
    }else{
        $tmp = str_replace("'", '"', $tmp);
        $condition = json_decode($tmp,true);
        if (json_last_error() != JSON_ERROR_NONE) {
            echo "unknown mongo condition arg: $tmp \n";
            exit;
        }
    }
    return $condition;
}


/**
 * 取得线上backup实例。一般在跑数据统计或者拉取数据时使用
 * @return Mongo
 */
function getUrl($url,$cached=false)
{
   $curl=new Curl();
   @mkdir('/tmp/' . date('Ymd'));
   $filename = '/tmp/'.date('Ymd').'/'.md5($url).'.jpg';
   if(file_exists($filename) && $cached){
       $ret = file_get_contents($filename);
   }else{
       $curl->download($url, $filename);
       $ret = file_get_contents($filename);
   }
   if(!$ret){
       throw new Exception('fetch url failed:'.$url);
   }
   return $ret;
}

/**
 * return file path if success,false if failed
 * @param $strUrl
 * @param cutPrefix array(left,top,right,bottom)
 * @return bool|string
 */
function downloadImage($strUrl,$cutPrefix=null,$cache=true)
{
    $curl = new Curl();
    $url_info = parse_url($strUrl);
    @mkdir('/tmp/' . date('Ymd'));
    $filename = '/tmp/'.date('Ymd').'/'.md5($url_info['path']).(is_array($cutPrefix)?'_'.join('_',$cutPrefix):'').'.jpg';
    if($cache && file_exists($filename)){//get cache
    }else{
        $filename = $curl->download($strUrl, $filename);
        if($filename && $cutPrefix){
            //do cut
            cutImage($filename, $cutPrefix);
        }
    }
    if(!file_exists($filename)){
       throw new Exception('can not download image '.$strUrl.' to '.$filename);
    }
    return $filename;
}

/**
 * @param $strUrl
 * @param cutPrefix array(left,top,right,bottom)
 */
function cutImage($fileName,$cutPrefix)
{
    if(count($cutPrefix) !== 4){
        throw new Exception('invalid param count '.join(',',$cutPrefix));
    }
    $imagick=new Imagick($fileName);
    $imagick->cropimage($imagick->getImageWidth()-$cutPrefix[0]+$cutPrefix[2],$imagick->getImageHeight()-$cutPrefix[1]+$cutPrefix[3],$cutPrefix[0],$cutPrefix[1]);
    $imagick->writeImage($fileName);
}

function getGeoByAddress($address,$cache=true)
{
    $ak = '2df9f6f998a6d671b74c5933dd2dc4c9';
    $url = "http://api.map.baidu.com/geocoder/v2/?address=" . urlencode($address) . "&output=json&ak=" . $ak;
    $content=getUrl($url,$cache);

    $arrRes = json_decode($content, TRUE);
    if (!is_array($arrRes)||empty($arrRes['result']['location']['lng'])){
        throw new Exception('can not get result:'.serialize($arrRes));
    }
    $geo = array($arrRes['result']['location']['lng'], $arrRes['result']['location']['lat']);
    return $geo;
}

