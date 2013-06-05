<?php

// 1. Traversal all static files , and save all file version caches to _v_c.php
define( 'REPLACE_CAHR' , '~' );
define( 'TEMPLATE_DIR', __DIR__ . '/../../application/views' );
define( 'SRC_DIR' , __DIR__ . '/../src');
define( 'PUB_DIR' , __DIR__ . '/../public');
define( 'VERSION_FILE' , __DIR__ . '/_v.json');
define( 'IMAGE_RELATIVE_FILE' , __DIR__ . '/_i.json');
define( 'CSS_COMPRESS_FILE' , __DIR__ . '/_c.json');

define( 'STA_YUICOMPRESSOR' , __DIR__ . '/yuicompressor.jar' );
include_once 'YUICompressor.php';
date_default_timezone_set('PRC');


function logger( $msg , $type = "INF" ){
    echo $type . "::" . $msg . "\n";
}
function loopDir( $dir , $process , $endfn = null){
    if(is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){

                $path = $dir . "/" . $file;

                if( $file == "." || $file == ".." ) continue;
                if( !empty( $endfn ) && $endfn( $path ) === false ) continue;

                if( is_dir ( $path ) ){
                    loopDir( $path , $process , $endfn );
                } else {
                    $process( $path );
                }
            }
            closedir($dh);
        }
    }
}
function cleanPath( $path ){
    $path = preg_replace( '/\\\+/', '/', $path );
    $path = preg_replace( '/\/+/', '/', $path );
    $paths = explode('/', $path );

    $r = array();
    foreach ($paths as $key => $value) {
        if( $value == '..' ){
            array_pop( $r );
        } else if( $value != '.' ){
            $r[] = $value;
        }
    }

    return join( '/' , $r );
}
function getRelativePath( $file , $path ){
    $file = cleanPath( $file );
    $path = cleanPath( $path );

    $file_arr = explode( '/' , $file );
    $path_arr = explode( '/' , $path );


    $r = array();
    for( $i = 0 , $len = count( $path_arr ); $i < $len ; $i++ ){
        if( $file_arr[ $i ] == $path_arr[ $i ] ){
            continue;
        }
        $r[] = "..";
    }
    for( $i -= count( $r ) , $len = count( $file_arr ) ; $i < $len ; $i++ ){
        $r[] = $file_arr[ $i ];
    }

    return cleanPath( join( '/' , $r ) );
}

function fixImageCacheVersion( $file  , $process = null ){
    $content = file_get_contents( $file );
    // refresh css image version
    $content = preg_replace_callback('/url\s*\(\s*([\'"]?)([^\'"?]+)(\?.*)?\1\s*\)/' ,
        function( $match ) use ( $file , $process ){

            $img_src = trim( $match[2] );
            $file_path = dirname( $file );

            // get real image path
            $img_real_src = cleanPath( $file_path . '/' . $img_src ) ;

            // get version
            if( !file_exists( $img_real_src ) ){
                logger("file $img_real_src not exist......" , "ERR");
                return $match[0];
            } else {
                if ( $process !== null ){
                    $process( $img_real_src , $file );
                }
                $time = filemtime( $img_real_src );
                if( !$time ){
                    $time = explode( ' ' , microtime() );
                    $time = $time[1];
                }

                return "url(" . getRelativePath( $img_real_src , $file_path ) . '?_=' . $time . ")";
            }
        } , $content );

    // write file
    return $content;
}


class BuildPublish {
    private static $error = array();

    // array('index/index.twig'=>array('a.js,b.css'))
    private static $staConfig = array();

    // array("image/a.png"=>array("css/a.css","css/b.css"));
    private static $imageRelative = array();

    // array("image/a.png"=>12312421412);
    private static $version = array();

    // array("image/a.png");
    private static $refresh = array();
    public static function start(){

        echo
"
---------------------------------------------
--                                         --
--          publish script run             --
--                                         --
---------------------------------------------
";

        // get version from VERSION_FILE
        self::$version = json_decode( file_get_contents( VERSION_FILE ) , true );
        self::$imageRelative = json_decode( file_get_contents( IMAGE_RELATIVE_FILE ) , true );
        if( empty(self::$imageRelative) ){
            self::$imageRelative = array();
        }

        // for debug
        // self::$version = array();
        self::getNeededRefreshFiles();
        // if no file refreshed
        if( count( self::$refresh ) == 0 ){
            logger('no file need to be refreshed ...');
            return;
        }

        // refresh public file
        self::refreshPublic();

        // refresh combine files
        self::refreshCombines();

        // update all cache files
        self::updateCacheFiles();

        // contratulation
        logger("contratulation , wish god with you !");
    }

    private static function getNeededRefreshFiles(){
        // only scan these dirs
        $dirs = array(
            SRC_DIR . '/js/',
            SRC_DIR . '/css/',
            SRC_DIR . '/image/',
            );

        foreach ($dirs as $key => $dir) {
            // scane the src dir , get version from every file
            $bDir = cleanPath( $dir );
            loopDir( $bDir , function( $filepath ){
                // get file relative path
                $filepath = cleanPath( $filepath );
                $path = getRelativePath( $filepath , SRC_DIR );

                // check version
                $v = filemtime( $filepath );
                $ov = isset( self::$version[ $path ] ) ? self::$version[ $path ] : 0;
                if( $ov < $v ){
                    // refresh the version
                    self::$version[ $path ] = $v;
                    // add config
                    self::$refresh[] = $path;
                }
            } , function( $path ){
                $path = cleanPath( $path );
                $paths = explode('/', $path );
                $name = end( $paths );
                return strpos( $name , '_' ) !== 0;
            });
        }

        //-----------------------------------------------------------------
        // first loop, find the images relatived css files
        // and add these css file to self::$refresh array

        foreach ( self::$refresh as $key => $file ) {
            // if not image file
            if( strpos( $file , '.css' ) > 0 || strpos( $file , '.js' ) > 0  ) continue;

            // get image file relative css
            if( isset( self::$imageRelative[ $file ] ) ){
                self::$refresh = array_merge( self::$refresh , self::$imageRelative[ $file ] );
            }
        }

        self::$refresh = array_unique( self::$refresh );
    }

    private static function refreshPublic(){

        $compressDesc =
"/*
 * combine file:
 * data: " . date('F j, Y, g:i a') . "
 */
";
        //-----------------------------------------------------------------
        foreach ( self::$refresh as $key => $file ) {
            $type = '';
            $srcfile = cleanPath( SRC_DIR . '/' . $file ) ;
            $pubfile = cleanPath( PUB_DIR . '/' . $file );

            // create dir
            $pubdir = dirname($pubfile);
            if( !file_exists($pubdir) )
                mkdir( $pubdir , '0777' , true );

            if( strpos( $file , '.css' ) > 0 )
                $type = 'css';
            else if( strpos( $file , '.js' ) > 0 )
                $type = 'js';

            if( !empty($type) ){ // compress the file
                // fix image version
                if( $type == 'css' ){

                    $content = fixImageCacheVersion( $srcfile , function( $imgPath , $srcfile ){

                        // save image and css file relativeship
                        $relayImagePath = getRelativePath( $imgPath , SRC_DIR );
                        $relayFilePath = getRelativePath( $srcfile , SRC_DIR );
                        if( isset( self::$imageRelative[ $relayImagePath ] ) ){
                            self::$imageRelative[ $relayImagePath ][] = $relayFilePath;
                        } else {
                            self::$imageRelative[ $relayImagePath ] = array( $relayFilePath );
                        }
                    });

                } else { // js file
                    $content = file_get_contents( $srcfile );
                }
                $yui = new YUICompressor( STA_YUICOMPRESSOR , __DIR__ , array(
                    'type' => $type
                    ));

                $yui->addString( $content );

                $content = $yui->compress();

                // write file
                logger("compress file [ $pubfile ]" , "YUI");
                file_put_contents( $pubfile , $compressDesc . $content );

            } else {
                logger("copy image
                 file [ $pubfile ]" , "CPY");
                copy( $srcfile , $pubfile );
            }
        }

    }

    private static function updateCacheFiles(){
        // 1. save version cache file
        // save cache file
        logger("save version cache file [ " . cleanPath( VERSION_FILE ) .  " ]" );
        file_put_contents( VERSION_FILE , json_encode( self::$version ) );
        // 2. save image , css files relationship cache
        //-----------------------------------------------------------------
        // array_unique self::$imageRelative
        foreach (self::$imageRelative as $key => $value) {
            self::$imageRelative[ $key ] = array_unique( $value );
        }
        logger("save image relative file config [ " . cleanPath( IMAGE_RELATIVE_FILE ) . " ]");
        // save imageRelation
        file_put_contents( IMAGE_RELATIVE_FILE , json_encode( self::$imageRelative ) );

        // 3. save combine config
        logger("save css compress file config [ " . cleanPath( CSS_COMPRESS_FILE ) . " ]");
        // write sta file
        file_put_contents( CSS_COMPRESS_FILE , json_encode( self::$staConfig ) );
    }
    // judge if web need to refresh combine file
    // 1. if need refresh files affect some combine files
    // 2. collect combine files from template first , and judge if
    //    template combine files changed.
    public static function refreshCombines(){

        // refresh compress css config
        self::generateCompressCssConfig();
        foreach ( self::$staConfig as $key => $stalist) {
            // refresh $stalist compress file , include css and js file
            foreach ($stalist['css'] as $key => $cssfile) {
                if( in_array( 'css/' . $cssfile , self::$refresh ) ){
                    // need refresh css compress file
                    self::combine( $stalist['css'] , 'css' );
                    break;
                }
            }
            foreach ($stalist['js'] as $key => $jsfile) {
                if( in_array( 'js/' . $jsfile , self::$refresh ) ){
                    // need refresh css compress file
                    self::combine( $stalist['js'] , 'js' );
                    break;
                }
            }
        }
    }

    private static function combine( $stalist , $type="css" ){
        $compressName = str_replace('/', REPLACE_CAHR , join(',' , $stalist) );
        $filePath = cleanPath( PUB_DIR . '/' . $type . '/' . $compressName );
        logger("generate combine file [ $filePath ]");

        // get version
        $content = '';
        foreach ($stalist as $key => $file) {
            $path = PUB_DIR . '/' . $type . '/' . $file;
            $content .= file_get_contents( $path ) . "\n";
        }

        // write file
        file_put_contents( $filePath , $content );
    }
    //-----------------------------------------------------------------
    // scan the template , and generage css compress config for template
    public static function generateCompressCssConfig(){
        logger("start generate compress css config...");
        loopDir( TEMPLATE_DIR , function( $file ){
            // is page template
            // a page template include string {% extends "base/frame.twig" %}
            $content = file_get_contents( $file );
            if( strpos( $content , 'base/frame.twig' ) !== false ){
                $pageTpl = getRelativePath( $file , TEMPLATE_DIR );
                self::$staConfig[ $pageTpl ] = array();
                // collect the css file , css file is like follows
                self::collectTemplateResource( $pageTpl , $file );
            }
        } );

        // array_unique
        foreach (self::$staConfig as $key => $value) {
            $value = array_unique( $value );
            $cssValue = array();
            $jsValue = array();

            foreach( $value as $k => $v ){
                if( strpos( $v , '.css' ) ){
                    $cssValue[] = $v;
                } else {
                    $jsValue[] = $v;
                }
            }
            self::$staConfig[ $key ] = array( "css" => $cssValue ,
                "js" => $jsValue ,
                );
        }


    }

    // Traversal the child templates and collect sta resources
    // {{ require("a.js,a/a.css" , {'a':"aaa",'b':"ccc"} ) }}
    public static function collectTemplateResource( $parentTpl , $template ){
        $content = file_get_contents( $template );

        // filter annotate
        $content = preg_replace( '/\{#.*?#\}/' , '' , $content );
        // collect current template resource
        preg_match_all( '/\{\{\s*require\s*\(\s*([\'"])([^\'"]+)\\1/' , $content , $match );

        $merge = self::$staConfig[ $parentTpl ];
        if( !empty( $match ) ){
            // save all tpl resources
            foreach ( $match[2] as $key => $value) {
                $merge = array_merge( $merge , explode( ',' , $value ) );
            }
        }
        self::$staConfig[ $parentTpl ] = $merge;

        // {% include "index/block.twig" %}
        preg_replace_callback( '/\{%\s*include\s+([\'"])([^"\']+)\\1\s*%\}/' , function( $match ) use( $parentTpl ){
            self::collectTemplateResource( $parentTpl , TEMPLATE_DIR . '/' . $match[2] );
        }, $content );
    }
}

/*
if( BuildPublish::cleanPath("e://11/22/33\\44\../55/66.png")
    == 'e:/11/22/33/55/66.png' )
    echo 'true';
else
    echo 'false';
*/
BuildPublish::start();

//echo BuildPublish::fixImageCache("udm_alloc_agent_array(databases)");
//BuildPublish::generateCompressCssConfig();