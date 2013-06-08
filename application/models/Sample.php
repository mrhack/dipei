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
    //:require表示，这个值不能为null
    // 没有:require则表示，这个值可以为null，但如果不是null，则必须满足对应的校验规则
    public function getValidator(){
        return array(
            // :require tag
            // string
            'n' => array("string:required","maxlength" => 20,"minlength" => 2,"message"=>
                _e('nick\'s length must between 2 and 20')),
            // range , if s is not null , it must be 0 or 1
            's' => array("range",array( 0 , 1 ),"message"=>_e("user's sex attribute must be 0 or 1" )),
            // number
            'age' => array("number","min" => 10,"max" => 30 , _e("message"=>"age must bigger than 10 and less than 30")),
            // date
            'bd' => array("date" , "message" => _e("user's birthday must be a Date value")),
            // email , use regexp to match
            'em' => array("email"),
            // regexp ,
            'desc.a.c' => array("regexp","/.*{0,170}/s" , "message"=>_e("user's desc's length must in 0 and 170")),
        );
    }
    public function valid( $value , $valid ){
        // validator the value
        $valType = explode( ':', $valid[0] );

        if( $value == null ){
            // validator required
            if( isset( $valType[1] ) && $valType[1] == 'required' ){
                throw Exception();
            }
        } else {
            $error = 0;
            switch( $valType[0] ){
                case "string":
                    // get str length , ugly method
                    preg_match_all('/./us', $value, $m);
                    $len = count( $m[0] );
                    if( isset($valid['maxlength']) ){
                        if( $len >= $valid['maxlength'] )
                            $error = 1;
                    }
                    if( isset($valid['minlength']) ){
                        if( $len <= $valid['minlength'] )
                            $error = 1;
                    }
                    break;
                case "number":
                    //TODO.. convent the value to number ?
                    if( isset($valid['max']) ){
                        if( $value >= $valid['max'] )
                            $error = 1;
                    }
                    if( isset($valid['min']) ){
                        if( $value <= $valid['min'] )
                            $error = 1;
                    }
                    break;
                case "enumerate":
                    if( !in_array( $value, $valid[1] ) ){
                        $error = 1;
                    }
                    break;
                case "email":
                    $pattern = '/^(\w|[.-])+@(\w+\.)+\w+$/';
                case "date":
                    $pattern = '/^(\d\d){1,2}-\d{1,2}-\d{1,2}$/';
                case "regexp":
                    if( !isset( $pattern ) ){
                        $pattern = $valid[1];
                    }
                    if( !preg_match( $pattern , $value ) ){
                        $error = 1;
                    }
                break;
            }

            if ( $error == 1 ){
                throw Exception( $valid['message'] );
            }
        }
    }
    public function validator( $data ){
        $valids = $this->getValidator();
        foreach ($valids as $key => $valid) {
            // get value
            $keys = explode( '.' , $key );
            $value = $data;
            foreach ($keys as $sk) {
                if( isset( $value[ $sk ] ) ){
                    $value = $value[ $sk ];
                } else {
                    $value = null;
                    break;
                }
            }

            $this->valid( $value , $valid );
        }
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