<?php
define( 'I18N_DIR' , __DIR__ . '/i18n' );
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 下午10:56
 */
class AppLocal{

    private static $local = 'zh_CN';
    private static $money = 'CNY';

    private static $properties = array();

    public static function defaultLocal()
    {
        return 'zh_CN';
    }

    public static function currentLocal()
    {
        return static::$local;
    }

    // get properties from current self::$local
    public static function init( $local = null ,$money =null){
        if(!empty($local)){
            self::$local = $local;
        }
        if(!empty($money)){
            //TODO 自动定位，非人民币即刀
            self::$money=$money;
        }
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
                // replace \#  === > #
                //$vs[0] = str_replace('\#', '#', $vs[0] );
                //$vs[1] = str_replace('\#', '#', $vs[1] );
                self::$properties[ trim( $vs[0] ) ] = trim( $vs[1] );
            }
        }
        //set lang/money cookie
        setcookie('lang', 'zh_CN');
        setcookie('money', 'CNY');
    }

    public static function getString( $propertyKey , $data = array() )
    {
        // get real property
        if( isset( self::$properties[ $propertyKey ] )
            && !empty( self::$properties[ $propertyKey ] ) ){
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