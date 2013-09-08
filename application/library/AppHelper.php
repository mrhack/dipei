<?php
/*
 * @date 2013-06-01
 * @author hdg1988@gmail.com
 * @desc Helper for lepei application
 */
/**
 * @method static AppHelper getInstance()
 */
class AppHelper{
    use Strategy_Singleton;
    // format string width given data
    // for example:
    // format("user name is #[name] ccc, and age is #[age]" , array("name"=>"hdg" , "age"=> 25))
    public function format( $str , $args ){
        return preg_replace_callback( "|#\[([^\]]+)\]|" , function ( $match ) use ( $args ) {
            if( isset($args[$match[1]]) ){
                return $args[$match[1]];
            }
            return '';
        } , $str );
    }

    // get str length , ugly method
    public function length( $str ){
        preg_match_all('/./us', $str, $m);
        return count( $m[0] );
    }

    public function isSuperUser($uid){
        return in_array($uid, array(
            5,6,7,8
        ));
    }

    public function isInternalNet()
    {
        $ip = $this->getIp();
        return $ip=== '127.0.0.1' || strpos($ip, '192.168.') === 0;
    }

    /**
     * @brief 获取用户ip
     * @param boolean $useInt 是否将ip转为int型，默认为true
     * @param boolean $returnAll 如果有多个ip时，是否会部返回。默认情况下为false
     * @param boolean $isUseForwarded 默认false, true|主要用户 主站发帖, 登陆 等功能， 去除了 HTTP_X_FORWARDED_FOR
     * @return string|array|false
     */
    public function getIp($useInt = false, $returnAll=false, $isUseForwarded = true) {
        $ip = getenv('HTTP_CLIENT_IP');
        if($ip && strcasecmp($ip, "unknown") && !preg_match("/192\.168\.\d+\.\d+/", $ip)) {
            return $this->_returnIp($ip, $useInt, $returnAll);
        }

        $isUseForwarded = (boolean) $isUseForwarded;
        if ($isUseForwarded) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
            if($ip && strcasecmp($ip, "unknown")) {
                return $this->_returnIp($ip, $useInt, $returnAll);
            }
        }

        $ip = getenv('REMOTE_ADDR');
        if($ip && strcasecmp($ip, "unknown")) {
            return $this->_returnIp($ip, $useInt, $returnAll);
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if($ip && strcasecmp($ip, "unknown")) {
                return $this->_returnIp($ip, $useInt, $returnAll);
            }
        }

        return false;
    }

    private function _returnIp($ip, $useInt, $returnAll) {
        if (!$ip) return false;

        $ips = preg_split("/[，, _]+/", $ip);
        if (!$returnAll) {
            $ip = $ips[count($ips)-1];
            return $useInt ? ip2long($ip) : $ip;
        }

        $ret = array();
        foreach ($ips as $ip) {
            $ret[] = $useInt ? ip2long($ip) : $ip;
        }
        return $ret;
    }

    public function getImages($json,&$out=array(),$_array=true)
    {
        if($out==null){
            $out=array();
        }
        if($_array){
            foreach($json as $j){
                $this->getImages($j,$out,false);
            }
        }else if(is_array($json)){
            if(isset($json['tag']) && $json['tag'] == 'img'){
                $src=$json['attr']['src'];
                //image path from own image_server
                if(preg_match('!^(http://)?'.IMAGE_SERVER_URL.'(.*)$!',$src,$matches)){
                    if(!in_array($matches[2],$out)){
                        $out[]=$matches[2];
                    }
                }
            }else if(isset($json['child'])){
                 $this->getImages($json['child'], $out,true);
            }
        }
        return $out;
    }
}