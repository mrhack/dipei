<?php
/*
 * render the base static in template files
 * <link href="/static/a.css,b.css,d.css,ji.css,de.css?_=7890" rel="stylesheet" />
 * <script href="/static/a.js,b/a.js,er/s.js,ew/ewrwe/were.js?_=123341"></script>
 */

define( 'STA_REPLACE_CHAR' , '~' );
define( 'STA_CACHE_DIR' , __DIR__ . '/_cache' );
define( 'STA_YUICOMPRESSOR' , __DIR__ . '/lib/yuicompressor.jar' );
define( 'STA_CSS_PATH' , __DIR__ . '/css' );
define( 'STA_JS_PATH' , __DIR__ . '/js' );


// get all file contents
function combine ( $files , $filePath ){

    $type = preg_match( '/\.css/' , $files[0] ) ? 'css' : 'js';

    $content = "";
    foreach ( $files as $key => $value ) {
        $content .= imageCacheContent( ( $type == 'css' ? STA_CSS_PATH : STA_JS_PATH ) . '/' . $value );
    }

    // INCLUDE COMPRESSOR CLASS
    include('lib/YUICompressor.php');
    // INVOKE CLASS
    $yui = new YUICompressor(STA_YUICOMPRESSOR, STA_CACHE_DIR, array(
        'type' => $type
        ));

    $yui->addString( $content );

    // COMPRESS
    $content = $yui->compress();
    file_put_contents( $filePath . '.' . $type , $content );
    echo $content;
}

function imageCacheContent( $file ){
    $content = file_get_contents( $file );
    return preg_replace( '/url\s*\(\s*([\'"]?)([^\'"]+)\1\s*\)/e' , "fixImageCache(\"\\2\",'" . $file .  "')" , $content );
}

function fixImageCache( $imgsrc , $file ){
    $img_src = trim( $imgsrc );
    $img_path = dirname( $imgsrc );
    $file_path = dirname( $file );

    // get real image path
    $img_real_src = $file_path . '/' . $img_src;

    // get version
    $time = filemtime( $img_real_src );
    if( !$time ){
        $time = explode( ' ' , microtime() );
        $time = $time[1];
    }

    return "url(" . getRelativePath( $img_real_src , STA_CACHE_DIR ) . '?_=' . $time . ")";
}

function getRelativePath( $img_file , $css_path ){
    //
    $img_file = cleanPath( $img_file );
    $css_path = cleanPath( $css_path );

    $img_file_arr = explode( '/' , $img_file );
    $css_path_arr = explode( '/' , $css_path );


    $r = array();
    for( $i = 0 , $len = count( $css_path_arr ); $i < $len ; $i++ ){
        if( $img_file_arr[ $i ] == $css_path_arr[ $i ] ){
            continue;
        }
        $r[] = "..";
    }
    for( $i -= count( $r ) , $len = count( $img_file_arr ) ; $i < $len ; $i++ ){
        $r[] = $img_file_arr[ $i ];
    }

    return join( '/' , $r );
}

function cleanPath( $path ){
    $path = preg_replace( '/\\\/', '/', $path );
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

$fileList = $_GET['f'];
$version = $_GET['_'];
$file = '';

if( !empty( $fileList ) ){
    $file = preg_replace( "/(\\\)|(\/)/" , STA_REPLACE_CHAR , $fileList ) . '-' . $version;
}

if( !empty( $file ) ){
    // judge if file is exist
    if( file_exists( STA_CACHE_DIR . '/' . $file )){
        echo file_get_contents( STA_CACHE_DIR . '/' . $file );
    } else {
        combine( explode( ',' , $fileList ) , STA_CACHE_DIR . '/' . $file );
    }
}