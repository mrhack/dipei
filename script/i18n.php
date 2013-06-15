<?php
// itera all these files, to collect i18n message:  like _e(["'] \1);
// controllers/*
// models/*
// views/*
include_once "../static/script/common.php";

class CollectI18n{
    private static $dirs = array(
        //"controllers",
        //"models",
        "application/views"
        );
    private static $keys = array();
    public static function init(){
        foreach ( self::$dirs as $key => $dir) {
            loopDir( APP_DIR . '/' . $dir , function( $file ){
                self::process( $file );
            });
        }

        $cons = array();
        foreach (self::$keys as $file => $keys) {
            $cons[] = '#' . $file;

            foreach ($keys as $value) {
                $cons[] = str_replace('#', '\#', $value ) . ' = ';
            }
        }
        // write file for translate
        writeFile( APP_DIR . '/application/library/i18n/_.properties' , join("\n" , $cons ) );
    }
    private static function process( $file ){
        $content = file_get_contents( $file );
        preg_match_all( '|_e\s*\(\s*([\'"])(.+?[^\\\])\1|' , $content , $match );
        $pathKey = getRelativePath( $file , APP_DIR );

        if( empty( $match[2] ) ) return;
        if( !isset( self::$keys[ $pathKey ] ) ){
            self::$keys[ $pathKey ] = array();
        }
        self::$keys[ $pathKey ] = array_unique( array_merge( self::$keys[ $pathKey ] , $match[2] ) );
    }
}

CollectI18n::init();