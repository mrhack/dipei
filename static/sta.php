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

 // load last version caches, from file
 class Sta {

    private static $needRefresh = false;
    private static $versionCachFile = "/_v_c.php";
    private static $config = array(
        'debug'     => false,
        'server'    => "127.0.0.1",
        'cgi'       => "/github/dipei/static/sta_cgi/",
        'csspath'   => "/css/",
        'jspath'    => "/js/",
        'imgpath'   => "/image/",
        'version'   => array()
        );

    /*
     * init render stalist and render the latest version
     */
    public static function init( $config , $strFiles ) {

        $js = array();
        $css = array();

        // can not pass the version
        unset( $config['version'] );

        $version = json_decode( file_get_contents( __DIR__ . self::$versionCachFile ) , true );
        // merge the config
        self::$config = array_merge( self::$config , $config );

        $files = explode( ',' , $strFiles );
        $cssVersions = array();
        $jsVersions = array();
        // depart the js and css , and get the versions
        foreach ($files as $key => $value) {
            # code...
            $v = self::getVersion( $value );
            if( preg_match( '/\.css$/' , $value ) ){
                $css[] = $value;
                $cssVersions[] = $v;
            } else if ( preg_match( '/\.js$/' , $value ) ){
                $js[] = $value;
                $jsVersions[] = $v;
            }
            $version[ $value ] = self::getVersion( $v );
        }

        // get the latest version
        if( !empty($cssVersions) ){
            $version[ join( ',' , $css ) ] = max( $cssVersions );
        }
        if( !empty($jsVersions) ){
            $version[ join( ',' , $js ) ] = max( $jsVersions );
        }

        // merge version
        self::$config['version'] = array_merge( self::$config['version'] , $version );

        $elements = array();
        $css = array_unique( $css );
        $js = array_unique( $js );
        if( self::$config['debug'] ){
            // debug model
            foreach( $css as $key => $value) {
                $elements[] = self::writeElement( $value , self::$config['csspath'] );
            }
            foreach( $js as $key => $value) {
                $elements[] = self::writeElement( $value , self::$config['jspath']);
            }
        } else {
            // product model
            $elements[] = self::writeElement( join( ',' , $css ) , self::$config['cgi'] );
            $elements[] = self::writeElement( join( ',' , $js ) , self::$config['cgi'] );
        }

        // save version
        if( self::$needRefresh ){
            file_put_contents( __DIR__ . self::$versionCachFile , json_encode( $version ) );
        }
        echo join( '' , $elements );
    }


    /*
     * get the file's last modify version
     */
    private static function getVersion( $file ){

        $version = self::$config['version'];
        // if exist
        if( isset( $version[ $file ] ) )
            return $version[ $file ];

        // get the file last modify version
        $time = null;

        self::$needRefresh = true;
        if( preg_match( '/\.css/' , $file ) ){
            if( file_exists( __DIR__ . self::$config['csspath'] . $file ) )
                $time = filemtime( __DIR__ . self::$config['csspath'] . $file );
        } else if( preg_match( '/\.js/' , $file ) ){
            if( file_exists( __DIR__ . self::$config['jspath'] . $file ) )
                $time = filemtime( __DIR__ . self::$config['jspath'] . $file );
        }

        if( !$time ){
            $time = explode( ' ' , microtime() );
            $time = $time[1];
        }
        return $time;
    }

    private static function writeElement( $file , $path ){
        if( empty($file) ) return '';

        $debug = self::$config['debug'];
        $version = self::$config['version'];
        $server = self::$config['server'];

        $path = 'http://' . $server .$path . $file . '?_=' . $version[$file];
        if( preg_match( '/\.css/' , $file ) ){
            return '<link href="' . $path . '" rel="stylesheet" type="text/css" />';
        } else if( preg_match( '/\.js/' , $file ) ){
            return '<script type="text/javascript" src="' . $path . '"></script>';
        }
    }
}

Sta::init(array() , $_GET['f']);