<?php
/**
 * User: wangfeng
 * Date: 13-5-28
 * Time: 下午11:45
 *
 * @method Twig_Adapter getView()
 */
class BaseController extends  Yaf_Controller_Abstract
{
    use AppComponent;

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

    public function render_ajax($code,$message='')
    {
        echo json_encode(array(
            'code'=>$code,
            'msg'=>$message
        )),"\n";
    }
}