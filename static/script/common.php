<?php
// 1. Traversal all static files , and save all file version caches to _v_c.php
define( 'REPLACE_CAHR' , '~' );
define( 'TEMPLATE_DIR', __DIR__ . '/../../application/views' );
define( 'BASE_TEMPALTE' , 'base/frame.twig');
define( 'APP_DIR' , __DIR__ . '/../..');
define( 'SRC_DIR' , __DIR__ . '/../src');
define( 'PUB_DIR' , __DIR__ . '/../public');
define( 'COMBINE_DIR' , PUB_DIR . '/combine');
define( 'VERSION_FILE' , __DIR__ . '/_v.json');
define( 'IMAGE_RELATIVE_FILE' , __DIR__ . '/_i.json');
define( 'COMBINE_CONFIG_FILE' , __DIR__ . '/_c.json');
define( 'STA_YUICOMPRESSOR' , __DIR__ . '/yuicompressor.jar' );

define( 'MODEL_LOADER_DIR' , __DIR__ . '/../public/js/lib');
define( 'MODEL_LOADER_DIR_COMBINE' , __DIR__ . '/../public/combine');
define( 'MODEL_LOADER_CONFIG_FILE' , __DIR__ . '/../public/js/config.js' );


function logger( $msg , $type = "INF" ){
    echo $type . " :: " . $msg . "\n";
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
function getTemplateContent( $file ){
    static $fileCache = array();
    if( isset( $fileCache[ $file ] ) )
        return $fileCache[ $file ];
    $content = file_get_contents( $file );

    // filter instruction {#xxxx#}
    $content = preg_replace( '/\{#.*?#\}/' , '' , $content );
    // save cache
    $fileCache[ $file ] = $content;
    return $content;
}

function writeFile( $file , $content ) {
    $dir = dirname( $file );
    if( !file_exists( $dir )){
        mkdir( $dir , '0777' , true );
    }
    file_put_contents( $file , $content );
}
function copyFile( $src , $tar ){
    $dir = dirname( $tar );
    if( !file_exists( $dir )){
        mkdir( $dir , '0777' , true );
    }
    copy( $src , $tar );
}
function seperateJsAndCss( $str ){
    $r = explode( ',' , $str );
    $css = array();
    $js = array();
    foreach ($r as $key => $value) {
        if( empty( $value ) ){
            continue;
        }
        $value = trim( $value );
        if( strpos( $value , '.css') ){
            $css[] = $value;
        } else {
            $js[] = $value;
        }
    }
    return array(
        "js" => array_unique( $js ),
        "css" => array_unique ( $css )
        );
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
    $same = true;
    for( $i = 0 , $len = count( $path_arr ); $i < $len ; $i++ ){
        if( $same && $file_arr[ $i ] == $path_arr[ $i ] ){
            continue;
        }
        $same = false;
        $r[] = "..";
    }
    for( $i -= count( $r ) , $len = count( $file_arr ) ; $i < $len ; $i++ ){
        $r[] = $file_arr[ $i ];
    }

    return join( '/' , $r );
}

function getCombineFiles( $combineConfigs ){
    $staType = array('headcss' , 'headjs' , 'pagecss' , 'pagejs');
    $arr = array();
    foreach ($combineConfigs as $tpl => $staArr) {
        foreach ($staType as $key => $type) {
            $filename = str_replace('/', REPLACE_CAHR , join(',' , $staArr[ $type ] ) );
            $arr[ $filename ] = $staArr[ $type ];
        }
    }
    return $arr;
}

function fixImageCacheVersion( $file  , $version , $process = null , $relayPath = null ){
    $content = file_get_contents( $file );
    // refresh css image version
    $content = preg_replace_callback('/url\s*\(\s*([\'"]?)([^)\'"]+?)(\?[^)\'"]*)?\1\s*\)/' ,
        function( $match ) use ( $file , $process , $relayPath , $version ){

            $img_src = trim( $match[2] );
            $file_path = dirname( $file );

            // get real image path
            $img_real_src = cleanPath( $file_path . '/' . $img_src ) ;

            // get version
            if( !file_exists( $img_real_src ) ){
                logger("file " . getRelativePath( $img_real_src , APP_DIR ) . " not exist......" , "--ERR--");
                return $match[0];
            } else {
                if ( $process !== null ){
                    $process( $img_real_src , $file );
                }

                // change image path to src dir
                $tmp = str_replace('/public/', '/src/', $img_real_src );
                $v = $version[getRelativePath( $tmp , SRC_DIR )];

                if( empty( $relayPath ) ){
                    $relayPath = $file_path;
                }
                return "url(" . getRelativePath( $img_real_src , $relayPath ) . '?_=' . $v . ")";
            }
        } , $content );

    // write file
    return $content;
}