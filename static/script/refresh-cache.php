<?php

// 1. Traversal all static files , and save all file version caches to _v_c.php
define( 'SRC_DIR' , __DIR__ . '/../src');
define( 'VERSION_FILE' , __DIR__ . '/_v_c.php');


function loopDir( $dir , $process ){
    if(is_dir($dir)){
        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){
                if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){
                    loopDir( $dir."/".$file."/" , $process );
                } else {
                    if($file!="." && $file!="..") {
                        $process( $dir , $file );
                    }
                }
            }
            closedir($dh);
        }
    }
}


class BuildPublish {
    private static $suffix = array( "js" , "css" , "" );
    public static function refresh(){
        // get version from VERSION_FILE
        $version = json_decode( file_get_contents( VERSION_FILE ) , true );

        // only scan these dirs
        $dirs = array(
            SRC_DIR . '/js/',
            SRC_DIR . '/css/',
            SRC_DIR . '/image/',
            );
        foreach ($dirs as $key => $dir) {
            // scane the src dir , get version from every file
            $bDir = self::cleanPath( $dir );
            loopDir( $bDir , function( $dir , $file ) use( $bDir ){
                // get file relative path
                $filepath = self::cleanPath( $dir . '/' . $file );

                $path = str_replace( $bDir , '', $filepath );
                echo $path . "\n";
                $v = filemtime( $filepath );
                echo $v;
            });
        }
    }

    public static function cleanPath( $path ){
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
}

BuildPublish::refresh();