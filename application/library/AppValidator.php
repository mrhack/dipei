<?php
/**
 * @desc validator component for lepei.
 * @author hdg1988@gmail.com
 * @date 2013-06-09
 *
 */
class AppValidator{
    // validator valid entrance
    // for example:
    // $data = array('n'=>"_asd!21s" , "age"=>"12" , "p"=>array('c'=>1));
    // $valids = array(
    //      // string
    //      'n' => array("string:required","maxlength" => 20,"minlength" => 2,"message"=>
    //          _e('nick\'s length must between 2 and 20')),
    //      // range , if s is not null , it must be 0 or 1
    //      's' => array("range",array( 0 , 1 ),"message"=>_e("user's sex attribute must be 0 or 1" )),
    //      // number
    //      'age' => array("number","min" => 10,"max" => 30 , _e("message"=>"age must bigger than 10 and less than 30")),
    //      // date
    //      'bd' => array("date" , "message" => _e("user's birthday must be a Date value")),
    //      // email , use regexp to match
    //      'em' => array("email"),
    //      // regexp ,
    //      'desc.a.c' => array("regexp","/.*{0,170}/s" , "message"=>_e("user's desc's length must in 0 and 170")),
    //  );
    /*
     *
     *
     */
    public static function valid( $data = array() , $valids = array() ){
        $error = array();
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

            $r = self::_valid( $key , $value , $valid );
            // if valid return false or a string value
            // means validator failure
            if( $r === false ){
                $error[] = _e( "#[label]'s value is illegal!" , array( "label" => $label ) );
            } else if( is_string( $r ) ){
                $error[] = $r;
            }
        }

        return $error;
    }

    //
    private static function _valid( $label ,  $value ,  $valid ){
        $valType = explode( ':', $valid[0] );
        if( in_array( 'required' , $valType) === false
            && $value === null ){
            return;
        }
        // validator tag from back to front
        for ($i = count( $valType ) - 1 ; $i >= 0 ; $i--) {
            $fn = $valType[ $i ];
            $r = self::$fn( $label , $value ,  $valid );
            // if an error occured , break the validator
            if( $r === false || is_string( $r ) )
                return $r;
        }
    }

    // :required
    private static function required( $label , $value =null , $valid = array() ){
        if( $value == null ){
            return isset( $valid['message'] ) ? $valid['message'] : _e("#[label] is required!" , array("label"=>$label));
        }
    }
    // string
    // attr: maxlength , minlength
    private static function string( $label , $value = null , $valid = array() ){
        // get str length , ugly method
        $len = AppHelper::length( $value );
        if( isset($valid['maxlength']) ){
            if( $len >= $valid['maxlength'] ){
                return isset( $valid['message'] ) ? $valid['message'] : _e("#[label]'s length is greater than #{length}!" ,
                    array("label"=>$label  , 'length'=> $valid['maxlength']));
            }
        }
        if( isset($valid['minlength']) ){
            if( $len <= $valid['minlength'] ){
                return isset( $valid['message'] ) ? $valid['message'] : _e("#[label]'s length is less than #{length}!" ,
                    array("label"=>$label , 'length'=> $valid['minlength']));
            }
        }
    }
    // number
    // attr: max , min
    private static function number( $label , $value = null , $valid = array() ){
        if( isset($valid['max']) ){
            if( $value >= $valid['max'] ){
               return isset( $valid['message'] ) ? $valid['message'] : _e("#[label] is greater than #{length}!" ,
                    array("label"=>$label , 'length'=> $valid['max']));
            }
        }
        if( isset($valid['min']) ){
            if( $value <= $valid['min'] ){
                return isset( $valid['message'] ) ? $valid['message'] : _e("#[label] is less than #{length}!" ,
                    array("label"=>$label , 'length'=> $valid['min']));
            }
        }
    }

    // enumerate
    // attr:
    private static function enumerate( $label , $value = null , $valid = array() ){
        if( !in_array( $value, $valid[1] ) ){
            return isset( $valid['message'] ) ? $valid['message'] : _e("#[label]'s value is illegal!" ,
                    array("label"=>$label) );
        }
    }
    // regexp
    // attr:
    private static function regexp( $label , $value = null , $valid = array() ){
        $pattern = $valid[1];
        if( !preg_match( $pattern , $value ) ){
            return isset( $valid['message'] ) ? $valid['message'] : _e("#[label]'s value is illegal!" ,
                    array("label"=>$label) );
        }
    }
    // email
    // attr:
    private static function email( $label , $value = null , $valid = array() ){
        return self::regexp( $label , $value ,
            array("regexp" ,
                '/^(\w|[.-])+@(\w+\.)+\w+$/' ,
                isset($valid['message']) ? $valid['message'] :  _e('email is not right!') ) );
    }
}