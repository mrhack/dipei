<?php
 // render base static file for templates
 /*
 * render statics just like follows:
 {{ render  }}
 * 0. load last version caches, if static dir has updates( TODO .. how ? ) , collect the latest update files's version. And renew the version cache.
 * 1. get params from template : debug model , sta list
 * 2. depart css and js , and get the latest version of all files which will be added to the query string.
 * 3. echo the HTMLElement , include link and javascript.
 *
 */
 include_once 'script/common.php';
 // load last version caches, from file
 class Sta {
    // use to save the page Sta resource
    private static $pageSta = "";
    // use to save the page var
    private static $pageVar = array();

    private static $pageStatic = null;
    private static $versionCachFile = "/script/_v.json";
    private static $pageStaticFile = "/script/_c.json";
    private static $version = array();
    private static $config = array(
        'debug'     => IS_DEBUG,
        'image_server_path' => "www.lepei.cc/public/img/",
        'server'    => "www.lepei.cc",
        'path'      => '/',
        'combinepath'=> 'combine/',
        'pubpath'   => 'public/',
        'devpath'   => 'src/',
        'csspath'   => "css/",
        'jspath'    => "js/",
        'pagejspath'=> "pagejs/",
        'imgpath'   => "image/",
        );

    /*
     * for twig function extension
     */
    public static function url( $src , $type='sta' , $width = null  , $height = null ){
        if( $type == "head" ){
            if( empty( $src ) ){
                $type = 'sta';
                $src = 'image/head.png';
            } else {
                $type = 'img';
            }
            $height = $width;
        }
        switch( $type ){
            case "img":
                // TODO .. get right size of image
                // -originwidth_originheight-currentwidth_currentheight.suffix
                preg_match( '/^(.*?)_(\d+)-(\d+)(_(\d+)-(\d+))?(\.\w+)/', $src , $match );
                if( count( $match ) > 0 ){
                    $prefix = $match[1];
                    $oW = $match[2];
                    $oH = $match[3];
                    if( $width && $height ){
                        $nW = $width;
                        $nH = $height;
                        $src = $match[1] . "_" . $oW . '-' . $oH . '_' . $width . '-' . $height . $match[7];
                    } else {
                        $src = $match[1] . "_" . $oW . '-' . $oH . $match[7];
                    }
                }
                return 'http://' . self::$config['image_server_path'] . $src;
            case "sta":
                $v = isset( self::$version[ $src ] ) ? self::$version[ $src ] : time();
                return 'http://' . self::$config['server'] . '/' .
                    self::$config[ self::$config['debug'] ? 'devpath' : 'pubpath' ]
                    . $src . '?_=' . $v;
        }
    }
    public static function setDebug( $bool ){
        self::$config['debug'] = $bool;
    }
    /*
     * init render stalist and render the latest version
     */
    public static function render( $config , $strFiles ) {
        // merge the config
        if( !empty($config))
            self::$config = array_merge( self::$config , $config );

        if( is_array( $strFiles ) )
            $strFiles = join(',' , $strFiles);
        // depart the js and css , and get the versions
        // notice::only read version, not generate version
        $staArr = seperateJsAndCss( $strFiles );
        $elements = array();

        if( self::$config['debug'] ){
            // debug model
            foreach( $staArr['css'] as $key => $value) {
                $elements[] = self::writeElement( $value );
            }
            foreach( $staArr['js'] as $key => $value) {
                $elements[] = self::writeElement( $value );
            }
        } else {
            // for publish
            $version = json_decode( file_get_contents( __DIR__ . self::$versionCachFile ) , true );
            // merge version
            if( !empty($version) )
                self::$version = array_merge( self::$version , $version );

            $compressCssName = str_replace('/', REPLACE_CAHR , join( ',' , $staArr['css'] ) );
            $compressJsName = str_replace('/', REPLACE_CAHR , join( ',' , $staArr['js'] ) );
            $cssVersions = array();
            $jsVersions = array();
            foreach ($staArr['css'] as $key => $value) {
                $cssVersions[] = self::getVersion( $value );
            }
            foreach ($staArr['js'] as $key => $value) {
                $jsVersions[] = self::getVersion( $value );
            }
            // get the latest version
            if( !empty($cssVersions) ){
                self::$version[ $compressCssName ] = max( $cssVersions );
            }
            if( !empty($jsVersions) ){
                self::$version[ $compressJsName ] = max( $jsVersions );
            }


            $elements[] = self::writeElement( $compressCssName );
            $elements[] = self::writeElement( $compressJsName );
        }
        return join( '' , $elements );
    }


    /*
     * get the file's last modify version
     */
    private static function getVersion( $file ){
        // if exist
        if( isset( self::$version[ $file ] ) )
            return self::$version[ $file ];

        // get the file last modify version
        $time = "";

        if( preg_match( '/\.css/' , $file ) ){
            if( isset( self::$version[ 'css/' . $file ] ) ){
                return self::$version[ 'css/' . $file ];
            }
            if( file_exists( __DIR__ . '/css/' . $file ) )
                $time = filemtime( __DIR__ . '/css/' . $file );
        } else if( preg_match( '/\.js/' , $file ) ){
            if( isset( self::$version[ 'js/' . $file ] ) ){
                return self::$version[ 'js/' . $file ];
            }
            if( file_exists( __DIR__ . '/js/' . $file ) )
                $time = filemtime( __DIR__ . '/js/' . $file );
        }
        return $time;
    }

    private static function writeElement( $file ){
        if( empty($file) ) return '';
        if( self::$config['debug'] ){
            $version = '';//time();
        } else {
            $version = self::$version[$file];
        }
        $server = self::$config['server'];

        $isOneFile = strpos( $file , "," ) === false;

        if( $isOneFile )
            $file = str_replace(REPLACE_CAHR, '/', $file);

        $path =  self::$config['path'] . self::$config[ self::$config['debug'] ? 'devpath' : 'pubpath' ];
        $csspath = self::$config[ self::$config['debug'] || $isOneFile ? 'csspath' : 'combinepath' ];
        $jspath = self::$config[ self::$config['debug'] || $isOneFile ? 'jspath' : 'combinepath' ];
        if( preg_match( '/\.css/' , $file ) ){
            $path = 'http://' . $server . $path . $csspath . $file . '?_=' . $version;
            return '<link href="' . $path . '" rel="stylesheet" type="text/css" />';
        } else if( preg_match( '/\.js/' , $file ) ){
            $path = 'http://' . $server . $path . $jspath . $file . '?_=' . $version;
            return '<script type="text/javascript" src="' . $path . '"></script>';
        }
    }



    // -------------------- for page var -----------------------------
    // add page sta entrance
    public static function addPageSta( $staList , $pageVar=array() ){
        // save page sta
        self::$pageSta = self::$pageSta . ',' . $staList;
        if( is_array( $pageVar ) )
            // save page var
            self::$pageVar = array_merge( self::$pageVar , $pageVar );
    }

    // render page sta resource and page var
    public static function renderPageJs( $tpl ){
        // render page var
        $html = array();
        if( !empty( self::$pageVar ) ){
            $html[] = '<script type="text/javascript">';
            $html[] = 'LP.setPageVar(' . json_encode( self::$pageVar ) . ');';
            $html[] = '</script>';
        }
        // only render page js files
        $sta = seperateJsAndCss( self::$pageSta );

        // if not debug model
        if( !self::$config["debug"] && isset( $tpl ) ){
            $sta = self::getPageJsList( $tpl );
        } else {
            // if is debug model, render css and js both.
            $sta = array_merge( $sta['js'],$sta['css'] );
        }

        $html[] = self::render( array() , $sta );
        return join( '' , $html );
    }

    public static function renderPageCss(){
        // if not public model.
        // get config from cache file
        $sta = seperateJsAndCss( self::$pageSta );
        return self::render( array() , $sta['css'] );
    }

    public static function isDebug(){
        return self::$config["debug"];
    }

    private static function getPageStatic( $tpl ){
        if( empty( self::$pageStatic ) )
            self::$pageStatic = json_decode( file_get_contents( __DIR__ . self::$pageStaticFile ) , true );
        return self::$pageStatic;
    }
    public static function getPageCssList( $tpl ){
        $staConfig = self::getPageStatic( $tpl );
        if( isset( $staConfig[ $tpl ] ) && isset( $staConfig[ $tpl ][ "pagecss" ] ) ){
            return $staConfig[ $tpl ]["pagecss"];
        } else {
            return "";
        }
    }
    public static function getPageJsList( $tpl ){
        $staConfig = self::getPageStatic( $tpl );
        if( isset( $staConfig[ $tpl ] ) && isset( $staConfig[ $tpl ][ "pagejs" ] ) ){
            return $staConfig[ $tpl ]["pagejs"];
        } else {
            return "";
        }
    }
}
