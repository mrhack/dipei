<?php
/*
 * entrance of dipei app
 */
define('ROOT_DIR', realpath(dirname(__FILE__).'/../'));
define('APPLICATION_PATH', ROOT_DIR . '/application');
define('TEST_DATA_DIR' , ROOT_DIR . '/test/data');
require_once dirname(__FILE__) . '/../vendor/autoload.php';

$tpl_dir = APPLICATION_PATH.'/views';

function extend($includeFile,$val){
    $parentVal = include_once $includeFile;
    return $val + $parentVal;
}
function getTemplateFile( $tpl ){
    global $tpl_dir;
    return $tpl_dir . '/' . $tpl . '.twig';
}
// get test data file from template path
// If file is not exist , create it.
function getTestDataPath( $tpl ){
    $path = TEST_DATA_DIR . '/' . $tpl . '.json';
    $path = str_replace('\\', '/', $path);
    preg_match( '/^(.*?)[\/][^\/]+$/' , $path , $r );
    if( is_array( $r ) && !is_dir($r[1]) ){
        mkdir( $r[1] , 0777 , true);
    }

    if( !file_exists( $path ) ){
        file_put_contents( $path , "{\n      \"stalist\" : \"\"\n}" );
    }
    return $path;
}
// this would find the inner inlcuded template
function getTemplates( $tpl ){
    $con = file_get_contents( getTemplateFile( $tpl ) );
    preg_match_all('/{%\s*include\s*([\'"])\s*(.*)\.twig\s*\\1.*%}/', $con, $match);
    $r = array( $tpl );
    if( is_array( $match[2] ) ){
        foreach ( $match[2] as $val ) {
             $r = array_merge( $r , getTemplates( $val ) );
        }
    }

    return $r;
}
// get test data from template files
// this would find the inner inlcuded template to merge
// all test data and return
function getTestData( $tpl ){
    if ( empty( $tpl ) ){
        return array();
    }

    $tpls = getTemplates( $tpl );

    $data = array();
    foreach ( $tpls as $v ) {
        // read content and convent to array
        $data = array_merge( $data , json_decode( preg_replace('/\/\*.*\*\//i', '' ,
                                    file_get_contents( getTestDataPath( $v ) )
                                    ) , true ) );
    }

    return $data;
}



//for debug
// $twig->addExtension(new Twig_Extension_Debug());

// get template path
$path = $_GET['path'];
$path = preg_replace("/^(\/)?(.*)\/$/", '$2', $path);

$fpath = $tpl_dir . '/' . $path . '.twig';
// judge if file is exist
if( !file_exists( $fpath ) ){
    echo '<span style="color:red;"> file is not exist , Please check you path!</span>';
    exit;
}


// get template content  to decide if add { block } to the file.
$content = file_get_contents( $fpath );
// if the template is not a page template , it is only a part of page
// we should add 'extend' to the content.
$loader = new Twig_Loader_Filesystem( $tpl_dir );
if( !preg_match('/base\/frame.twig/', $content) ){
    $content = '{% extends "base/frame.twig" %}{% block content %}' . $content . '{% endblock %}';
    $loader1 = new Twig_Loader_Array(array(
        'tmp.html' => $content
    ));
    $loader = new Twig_Loader_Chain( array( $loader , $loader1 ) );
    $tpl = 'tmp.html';
} else {
    $tpl = $path.'.twig';
}

$twig = new Twig_Environment( $loader, array(
    'cache'=>false,
    'debug'=>true
));

$val = file_get_contents( 'global.json' );
$val = array_merge( json_decode( $val , true ) , getTestData( $path ));
echo $twig->render( $tpl ,  $val );

?>
