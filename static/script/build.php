<?php
include_once 'common.php';
include_once 'YUICompressor.php';
date_default_timezone_set('PRC');

class BuildPublish {
    private static $error = array();

    // array('index/index.twig'=>array(
    //  "basejs" => "a.js,b.js",
    //  "basecss" => "a.css,b.css",
    //  "pagejs" => "page.js,page1.js",
    //  "pagecss" => "p.css"
    // ));
    private static $staCombineConfig = array();

    // array("image/a.png"=>array("css/a.css","css/b.css"));
    private static $imageRelative = array();

    // array("image/a.png"=>12312421412);
    private static $version = array();

    // all => all needed refresh files
    // modified => the real file which was modified
    // relative => the relative file which has relationship with modified file, this
                //is normally css file , when some image was modified
    // combine  => the combine file , need refreshed

    // array("all" =>array( "image/a.png" ) , "modified" => array( "" ) , "relative" =>array(),"combine"=>array("") );
    private static $refresh = array(
        "all" => array(),
        "modified" => array(),
        "relative" => array(),
        "combine" => array(),
        );
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
        if( count( self::$refresh["modified"] ) > 0 ){
            // echo all need refresh files
            logger('modified files as follows:' , 'COF');
            logger('-------------------------' , 'COF');
            foreach ( self::$refresh["modified"] as $key => $value) {
                logger( '[modify time : ' . date("m-d h:m:s", self::$version[ $value ] ) . ']  ' . $value , 'COF');
            }
            if( count( self::$refresh["relative"] ) > 0 ){
                logger(  "" , 'COF' );
                logger('relative files as follows:' , 'COF');
                logger('-------------------------' , 'COF');
                foreach ( self::$refresh["relative"] as $key => $value) {
                    logger(  $value , 'COF' );
                }
            }
            //TODO...
            if( count( self::$refresh["combine"] ) > 0 ){
                logger(  "" , 'COF' );
                logger('combine files as follows:' , 'COF');
                foreach ( self::$refresh["combine"] as $key => $value) {
                    logger(  $value , 'COF' );
                }
            }

            logger(' please check updated files , press enter key to continue...' , 'COF');
            fgets(STDIN);

            logger("===== start : copy images and compress js , css files ==== ");
            // refresh public file
            self::refreshPublic();
            logger("===== finsished : copy images and compress js , css files ==== ");
            logger("");
        } else {
            logger('no file need to be refreshed ...');
        }

        logger("===== start : refresh combines ==== ");
        // refresh combine files
        self::refreshCombines();
        logger("===== finsished : refresh combines ==== ");
        logger("");

        logger("===== start : update config files ==== ");
        // update all config files
        self::updateConfigFiles();
        logger("===== finsished : update config files ==== ");
        logger("");
        // contratulation
        logger("congratulation , wish god with you !");
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
                    self::$refresh["modified"][] = $path;
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

        $relatived = array();
        foreach ( self::$refresh["modified"] as $key => $file ) {
            // if not image file
            if( strpos( $file , '.css' ) > 0 || strpos( $file , '.js' ) > 0  ) continue;

            // get image file relative css
            if( isset( self::$imageRelative[ $file ] ) ){
                $relatived = array_merge( $relatived , self::$imageRelative[ $file ] );
            }
        }
        foreach ( $relatived as $key => $file ) {
            if( !in_array( $file , self::$refresh["modified"]) ){
                self::$refresh["relative"][] = $file;
            }
        }

        self::$refresh["relative"] = array_unique( self::$refresh["relative"] );

        // update all
        self::$refresh["all"] = array_unique( array_merge( self::$refresh["modified"] , self::$refresh["relative"] ) );
    }

    /*
     * update javascript model loader config
     */
    public static function updateModelLoaderConfig(){
        // 1. get config file content
        $con = file_get_contents( MODEL_LOADER_CONFIG_FILE );
        // 2. convent it to array object
            // 2.1 extract json content
        $con = preg_replace('/^.*seajs.config\((.*)\);\s*$/s', "\\1", $con );
            // 2.3 convent to array object
        $con = preg_replace('/([^,{"\']+):/', "\"\\1\":", $con );
        $config = json_decode( $con , true );
        // 3. update every config version
        if( isset( $config['shim'] ) ){
            $shim = &$config['shim'];
            foreach ($shim as $key => $c) {
                $f = $c['src'];
                if( strpos( $f , 'http://') === false ){
                    // get file version
                    // filter for ?xxx and #xxx
                    preg_match( '/^([^?#]+)(\?|#).*$/', $f , $match);
                    if( !empty( $match ) ){
                        $f = $match[1];
                    } else {
                        // add '.js' name
                        $f = str_replace( '.js.js', '.js', $f . '.js');
                    }
                    $fpath = getRelativePath( MODEL_LOADER_DIR . '/' . $f , PUB_DIR );
                    $shim[ $key ][ 'src' ] = $f . '?_=' . self::$version[ $fpath ];
                }
            }
        };

        if( isset( $config['alias'] )){
            $alias = &$config['alias'];
            foreach ($config['alias'] as $key => $f) {
                if( strpos( $f , 'http://') === false ){
                    // get file version
                    // get file version
                    // filter for ?xxx and #xxx
                    preg_match( '/^([^?#]+)(\?|#).*$/', $f , $match);
                    if( !empty( $match ) ){
                        $f = $match[1];
                    } else {
                        // add '.js' name
                        $f = str_replace( '.js.js', '.js', $f . '.js');
                    }
                    $fpath = getRelativePath( MODEL_LOADER_DIR . '/' . $f , PUB_DIR );
                    $alias[ $key ] = $f . '?_=' . self::$version[ $fpath ];
                }
            }
        }
        // 4. write it back
        writeFile( MODEL_LOADER_CONFIG_FILE , 'seajs.config(' . json_encode( $config ) . ');' );
        // 5. game over
    }

    private static function refreshPublic(){

        $compressDesc =
"/*
 * compress file:
 * data: " . date('F j, Y, g:i a') . "
 */
";
        //-----------------------------------------------------------------
        foreach ( self::$refresh['all'] as $key => $file ) {
            $type = '';
            $srcfile = cleanPath( SRC_DIR . '/' . $file ) ;
            $pubfile = cleanPath( PUB_DIR . '/' . $file );

            if( strpos( $file , '.css' ) > 0 )
                $type = 'css';
            else if( strpos( $file , '.js' ) > 0 )
                $type = 'js';

            if( !empty($type) ){ // compress the file
                // write file
                logger("compress file [ " . getRelativePath( $pubfile , APP_DIR ) . " ]" , "YUI");
                // fix image version
                if( $type == 'css' ){

                    $content = fixImageCacheVersion( $srcfile , self::$version , function( $imgPath , $srcfile ){

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

                writeFile( $pubfile , $compressDesc . $content );

            } else {
                logger("copy image file [ " . getRelativePath( $pubfile , APP_DIR ) . " ]" , "CPY");
                copyFile( $srcfile , $pubfile );
            }
        }

    }

    private static function updateConfigFiles(){
        // 1. save version cache file
        // save cache file
        logger("save version cache file [ " . getRelativePath( VERSION_FILE , APP_DIR ) .  " ]" );
        writeFile( VERSION_FILE , json_encode( self::$version ) );
        // 2. save image , css files relationship cache
        //-----------------------------------------------------------------
        // array_unique self::$imageRelative
        foreach (self::$imageRelative as $key => $value) {
            self::$imageRelative[ $key ] = array_unique( $value );
        }
        logger("save image relationship config [ " . getRelativePath( IMAGE_RELATIVE_FILE , APP_DIR ) . " ]");
        // save imageRelation
        writeFile( IMAGE_RELATIVE_FILE , json_encode( self::$imageRelative ) );

        // 3. save combine config
        logger("save css combine config [ " . getRelativePath( COMBINE_CONFIG_FILE , APP_DIR ) . " ]");
        // write sta file
        writeFile( COMBINE_CONFIG_FILE , json_encode( self::$staCombineConfig ) );

        // 4. save model loader config
        logger("save model loader config [ " . getRelativePath( MODEL_LOADER_CONFIG_FILE , APP_DIR ) . " ]");
        self::updateModelLoaderConfig();

    }
    // judge if web need to refresh combine file
    // 1. if need refresh files affect some combine files
    // 2. collect combine files from template first , and judge if
    //    template combine files changed.
    public static function refreshCombines(){
        $refreshedFiles = array();
        // refresh compress css config
        self::generateCombineConfig();
        // TODO... need to check if , a new combine file exist in tempalte change
        $oldCombineConfig = json_decode( file_get_contents( COMBINE_CONFIG_FILE ) , true );
        $oldCombineFiles = getCombineFiles( $oldCombineConfig );
        $newCombineFiles = getCombineFiles( self::$staCombineConfig );

        // delete not in used combine files
        foreach ($oldCombineFiles as $filename => $fileArr) {
            // if not in use , delete it
            if( !empty( $filename ) && !isset( $newCombineFiles[ $filename ] ) ){
                logger('remove combine file [ ' . cleanPath( getRelativePath( COMBINE_DIR . '/' . $filename , APP_DIR ) ) . ' ]');
                unlink( COMBINE_DIR . '/' . $filename );
            }
        }
        // add new combine files
        foreach ($newCombineFiles as $filename => $fileArr) {
            if( !isset( $oldCombineFiles[ $filename ] ) ){
                // build combine file
                $refreshedFiles[ join(',' , $fileArr) ] = 1;
                self::combine( $fileArr , strpos( $filename , '.css' ) === false ? 'js' : 'css' );
            }
        }
        // generate combine files
        foreach ( self::$staCombineConfig as $tpl => $stalist) {
            // refresh $stalist compress file , include css and js file
            foreach ($stalist['headcss'] as $key => $cssfile) {
                if( in_array( 'css/' . $cssfile , self::$refresh['all'] ) ){
                    // need refresh css compress file
                    $key = join(',' , $stalist['headcss'] );
                    if( !isset( $refreshedFiles[ $key ] ) ){
                        $refreshedFiles[ $key ] = 1;
                        self::combine( $stalist['headcss'] , 'css' );
                    }
                    break;
                }
            }
            foreach ($stalist['pagecss'] as $key => $cssfile) {
                if( in_array( 'css/' . $cssfile , self::$refresh['all'] ) ){
                    // need refresh css compress file
                    $key = join(',' , $stalist['pagecss'] );
                    if( !isset( $refreshedFiles[ $key ] ) ){
                        $refreshedFiles[ $key ] = 1;
                        self::combine( $stalist['pagecss'] , 'css' );
                    }
                    break;
                }
            }
            foreach ($stalist['headjs'] as $key => $jsfile) {
                if( in_array( 'js/' . $jsfile , self::$refresh['all'] ) ){
                    // need refresh css compress file
                    $key = join(',' , $stalist['headjs'] );
                    if( !isset( $refreshedFiles[ $key ] ) ){
                        $refreshedFiles[ $key ] = 1;
                        self::combine( $stalist['headjs'] , 'js' );
                    }
                    break;
                }
            }
            foreach ($stalist['pagejs'] as $key => $jsfile) {
                if( in_array( 'js/' . $jsfile , self::$refresh['all'] ) ){
                    // need refresh css compress file
                    $key = join(',' , $stalist['pagejs'] );
                    if( !isset( $refreshedFiles[ $key ] ) ){
                        $refreshedFiles[ $key ] = 1;
                        self::combine( $stalist['pagejs'] , 'js' );
                    }
                    break;
                }
            }
        }
    }

    private static function combine( $stalist , $type="css" ){
        // if stalist is not a array, or array length is zero
        if( !is_array( $stalist ) || count( $stalist ) == 0 ) return;
        $compressName = str_replace('/', REPLACE_CAHR , join(',' , $stalist ) );
        $filePath = cleanPath( COMBINE_DIR . '/' . $compressName );
        logger("generate combine file [ " . getRelativePath( $filePath , APP_DIR ) . " ]");
        // get version
        $content = '';
        foreach ($stalist as $key => $file) {
            $fpath = PUB_DIR . '/' . $type . '/' . $file;
            // fix image path
            if( !file_exists( $fpath ) ){
                logger("file [ " . getRelativePath( $fpath , APP_DIR ) . ' ] not exist!' , '--ERR--');
                continue;
            }
            $content .= fixImageCacheVersion( $fpath , self::$version , null , dirname( $filePath ) ) . "\n";
        }
        // write file
        writeFile( $filePath , $content );
    }
    //-----------------------------------------------------------------
    // scan the template , and generage css compress config for template
    public static function generateCombineConfig(){
        logger("start generate combine config...");
        loopDir( TEMPLATE_DIR , function( $file ){
            // is page template
            // a page template include string {% extends "base/frame.twig" %}
            $content = file_get_contents( $file );
            if( strpos( $content , 'base/frame.twig' ) !== false ){
                $pageTpl = getRelativePath( $file , TEMPLATE_DIR );
                self::$staCombineConfig[ $pageTpl ] = array();
                // collect head css and js file
                $headSta = seperateJsAndCss( self::collectTemplateHeadResource( $file ) );
                self::$staCombineConfig[ $pageTpl ] ["headcss"] = $headSta["css"];
                self::$staCombineConfig[ $pageTpl ] ["headjs"] = $headSta["js"];
                // collect the css file , css file is like follows
                $pageSta = seperateJsAndCss( self::collectTemplatePageResource( $pageTpl , $file ) );
                self::$staCombineConfig[ $pageTpl ] ["pagecss"] = $pageSta["css"];
                self::$staCombineConfig[ $pageTpl ] ["pagejs"] = $pageSta["js"];
            }
        } );

    }

    public static function collectTemplateHeadResource( $tpl ){
        $content = getTemplateContent( $tpl );

        preg_match_all( '/\{\{\s*sta\s*\(\s*([\'"])([^\'"]+)\\1/' , $content , $match );

        $headSta = "";
        if( $match[2] ){
            $headSta = join(',' , $match[2] );
        } else if( $tpl != BASE_TEMPALTE ){ // read from base.twig
            return self::collectTemplateHeadResource( TEMPLATE_DIR . '/' . BASE_TEMPALTE );
        }

        return $headSta;
    }
    // Traversal the child templates and collect sta resources
    // {{ require("a.js,a/a.css" , {'a':"aaa",'b':"ccc"} ) }}
    public static function collectTemplatePageResource( $parentTpl , $template ){
        $content = getTemplateContent( $template );
        // collect current template resource
        preg_match_all( '/\{\{\s*require\s*\(\s*([\'"])([^\'"]+)\\1/' , $content , $match );

        $merge = "";
        if( $match[2] ){
            // save all tpl resources
            $merge .= join( ',' , $match[2] );
        }

        // {% include "index/block.twig" %}
        preg_replace_callback( '/\{%\s*include\s+([\'"])([^"\']+)\\1\s*%\}/' , function( $match ) use( $parentTpl , &$merge ){
            $merge = $merge . ',' . self::collectTemplatePageResource( $parentTpl , TEMPLATE_DIR . '/' . $match[2] );
        }, $content );

        return $merge;
    }
}

BuildPublish::start();