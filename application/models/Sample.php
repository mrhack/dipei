<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author wangfeng
 */
class SampleModel extends AppComponent{


    public function __construct(  ) {
    }

    public function getFormatSchema(){
        return array(
            'n' => 'name',
            's' => 'sex',
            'em' => 'email',
            'pw' => 'password',
            'h' => 'head',
            'dsc' => 'desc',
            'c_t' => 'create_time',
            'ims' => 'images',
            'lk' => 'like',
            'm' => 'money',
            'l' => 'lang',
            'ls' => 'license',
            'con' => 'contact',
            'l_t' => 'lepei_type',
            'p' => array(
                'project',//self name
                't' => 'title',
                'tm' => 'travel_schemas',
                'ts' => 'travel_services',
                'ds' => array(
                    'days',
                    'dsc' => 'desc',
                    'lk' => 'like'
                )),
        );
    }
    public function getValidator(){
        return array(
            'n' => array("string:required","maxlength" => 20,"minlength" => 2,),
            's' => array("range",array(0,1)),
            'age' => array("number","min" => 10,"max" => 30),
            'em' => array("email"),
            'dsc' => array("string","maxlength" => 120,),
        );
    }

    // return instance of current model
    public static function fetchOne( $condition = array() , $fields = array() ){

    }

    // 如果没有id 需要save，则需要校验所有的验证规则
    // 有id，表示需要更新，则只要校验当前属性的规则即可
    public function save(){

    }
    // 删除当前所有
    public function del(){

    }
    //
    public function set( $attribute =array() ){

    }
}
