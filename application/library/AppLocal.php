<?php
define( 'I18N_DIR' , __DIR__ . '/i18n' );
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 下午10:56
 */
class AppLocal{

    private static $local = 'zh_cn';

    private static $properties = array();

    public static function setLocal($local){
        if(!empty($local)){
            self::$local=$local;
        }
    }

    // get properties from current self::$local
    public static function getProperties(){
        if( count( self::$properties ) > 0 ){
            return self::$properties;
        } else {
            // get properties from file
            // filter content
            // # ....
            // name = 名字
            $i18nFile = I18N_DIR . '/' . self::$local . '.properties';
            if( !file_exists( $i18nFile ) )
                return self::$properties;
            $con = file_get_contents( $i18nFile );
            $con = preg_replace('/^\s*#.*\n/m', '', $con);
            $p = explode("\n", $con);
            foreach ($p as $key => $value) {
                $vs = explode( '=', $value );
                if( count( $vs ) != 2 ){
                    continue;
                }
                self::$properties[ trim( $vs[0] ) ] = trim( $vs[1] );
            }
        }
    }

    public static function getString( $propertyKey , $data = array() )
    {
        // get real property
        if( isset( self::$properties[ $propertyKey ] ) ){
            return empty( $data ) ? self::$properties[ $propertyKey ] :
                AppHelper::format( self::$properties[ $propertyKey ] , $data );
        }
        return empty( $data ) ? $propertyKey:
                AppHelper::format( $propertyKey , $data );
    }

    private static function checkLocal(){

    }
}
// TODO ..  set local first
/*
 * @desc $k would be like this: #[name] is god , so it should takes a variable
 * @$k : the key value
 * @$data: if $k needs variables, this arguments needed.
 * @return { string }
 */
function _e( $k , $data = array() ){
    return AppLocal::getString($k, $data);
}