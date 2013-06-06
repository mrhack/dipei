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
 define( 'REPLACE_CAHR' , '~' );
 // load last version caches, from file
 class Sta {
    // use to save the page Sta resource
    private static $pageSta = array();
    // use to save the page var
    private static $pageVar = array();
    private static $needRefresh = false;
    private static $versionCachFile = "/script/_v.json";
    private static $version = array();
    private static $config = array(
        'debug'     => true,
        'server'    => "lepei.cc",
        'path'      => '/',
        'pubpath'   => 'public/',
        'devpath'   => 'src/',
        'csspath'   => "css/",
        'jspath'    => "js/",
        'imgpath'   => "image/",
        );

    /*
     * init render stalist and render the latest version
     */
    public static function render( $config , $strFiles ) {

        $js = array();
        $css = array();

        $version = json_decode( file_get_contents( __DIR__ . self::$versionCachFile ) , true );
        // merge version
        self::$version = array_merge( self::$version , $version );
        // merge the config
        self::$config = array_merge( self::$config , $config );

        $files = explode( ',' , $strFiles );
        $cssVersions = array();
        $jsVersions = array();

        // depart the js and css , and get the versions
        foreach ($files as $key => $value) {
            # code...
            if( empty($value) ) continue;
            $v = self::getVersion( $value );
            if( preg_match( '/\.css$/' , $value ) ){
                $css[] = $value;
                $cssVersions[] = $v;
            } else if ( preg_match( '/\.js$/' , $value ) ){
                $js[] = $value;
                $jsVersions[] = $v;
            }
            self::$version[ $value ] = $v;
        }


        $elements = array();
        $css = array_unique( $css );
        $js = array_unique( $js );


        if( self::$config['debug'] ){
            // debug model
            foreach( $css as $key => $value) {
                $elements[] = self::writeElement( $value );
            }
            foreach( $js as $key => $value) {
                $elements[] = self::writeElement( $value );
            }
        } else {

            $compressCssName = str_replace('/', REPLACE_CAHR , join( ',' , $css ));
            $compressJsName = str_replace('/', REPLACE_CAHR , join( ',' , $js ));
            // get the latest version
            if( !empty($cssVersions) ){
                self::$version[ $compressCssName ] = max( $cssVersions );
            }
            if( !empty($jsVersions) ){
                self::$version[ $compressJsName ] = max( $jsVersions );
            }

            // if only one css file
            $cssfile = count( $css ) == 1 ? $css[0] : $compressCssName;
            $jsfile = count( $js ) == 1 ? $js[0] : $compressJsName;

            $elements[] = self::writeElement( $cssfile );
            $elements[] = self::writeElement( $jsfile );
        }

        // need to refresh the version of static files
        if( self::$needRefresh ){
            file_put_contents(__DIR__ . self::$versionCachFile , json_encode( self::$version ) );
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
            if( file_exists( __DIR__ . '/css/' . $file ) )
                $time = filemtime( __DIR__ . '/css/' . $file );
        } else if( preg_match( '/\.js/' , $file ) ){
            if( file_exists( __DIR__ . '/js/' . $file ) )
                $time = filemtime( __DIR__ . '/js/' . $file );
        }

        if( !empty( $time ) ){
            self::$version[ $file ] = $time;
            self::$needRefresh = true;
        }
        return $time;
    }

    private static function writeElement( $file ){
        if( empty($file) ) return '';

        $version = self::$version[$file];
        $server = self::$config['server'];

        $path =  self::$config['path'] . self::$config[ self::$config['debug'] ? 'devpath' : 'pubpath' ];
        $csspath = self::$config['csspath'];
        $jspath = self::$config['jspath'];

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
        self::$pageSta = array_merge( self::$pageSta , explode( ',' , $staList ) );
        if( is_array( $pageVar ) )
            // save page var
            self::$pageVar = array_merge( self::$pageVar , $pageVar );
    }

    // render page sta resource and page var
    public static function renderPageSta(){
        // render page var
        $html = array();
        if( !empty( self::$pageVar ) ){
            $html[] = '<script type="text/javascript">';
            $html[] = 'LP.setPageVar(' . json_encode( self::$pageVar ) . ');';
            $html[] = '</script>';
        }
        $html[] = self::render( array() , join( ',' , self::$pageSta ) );
        return join( '' , $html );
    }
}
