<?php
/**
 * Created by JetBrains PhpStorm.
 * User: taoqili
 * Date: 12-7-18
 * Time: 上午11: 32
 * UEditor编辑器通用上传类
 */
class AppUploader
{
    use AppComponent;

    private $fileField;            // 文件域名
    private $file;                 // 文件上传对象
    private $config = array(
        "allowFiles" => "image",
        "maxSize" => 5120,
        "savePath" => "public/img"
    );               // 配置信息
    private $oriName;              // 原始文件名
    private $fileName;             // 新文件名
    private $fullName;             // 完整文件名,即从当前配置目录开始的URL
    private $fileSize;             // 文件大小
    private $width;                // 图片文件的宽
    private $height;               // 图片文件的高
    private $fileType;             // 文件类型
    private $stateInfo;            // 上传状态信息,
    private $extMap = array(
        "image" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp")
        );

    private $stateMap = array(     // 上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS" ,                // 上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制" ,
        "文件大小超出 MAX_FILE_SIZE 限制" ,
        "文件未被完整上传" ,
        "没有文件被上传" ,
        "上传文件为空" ,
        "POST" => "文件大小超出 post_max_size 限制" ,
        "SIZE" => "文件大小超出网站限制" ,
        "TYPE" => "不允许的文件类型" ,
        "DIR" => "目录创建失败" ,
        "IO" => "输入输出错误" ,
        "UNKNOWN" => "未知错误" ,
        "MOVE" => "文件保存时出错"
    );

    private $_fileErrorMap=array(
        Constants::CODE_SUCCESS,
        Constants::CODE_UPLOAD_OVERFLOW_POST,
        Constants::CODE_UPLOAD_UNCOMPLETED,
        Constants::CODE_UPLOAD_EMPTY_LIST,
        Constants::CODE_UPLOAD_EMPTY_FILE
    );

    /**
     * 构造函数
     * @param string $fileField 表单名称
     * @param array $config  配置项
     */
    public function __construct( $fileField , $config = array())
    {
        $this->fileField = $fileField;
        $this->config = array_merge( $this->config , $config ) ;
        $this->stateInfo = $this->stateMap[ 0 ];
    }

    /**
     * 上传文件的主处理方法
     * @param $base64
     * @return mixed
     */
    public function upFile( $base64 =false )
    {
        $this->getLogger()->info('upload file',array('filed'=>$this->fileField,'files'=>$_FILES));
        //处理base64上传
        if ( "base64" == $base64 ) {
            $content = $_POST[ $this->fileField ];
            $this->base64ToImage( $content );
            return;
        }

//        //处理普通上传
//        if( !isset( $_FILES[ $this->fileField ] ) ){
//            $this->getLogger()->error('not found upload field:'.$this->fileField);
//            throw new AppException(Constants::CODE_UPLOAD_FAILED);
//        }
        $file = $this->file = $_FILES[ $this->fileField ];
        if ( !$file ) {
            $this->getLogger()->error('upload overflow post');
            $this->stateInfo = $this->getStateInfo( 'POST' );
            throw new AppException(Constants::CODE_UPLOAD_OVERFLOW_POST);
        }
        if ( $this->file[ 'error' ] ) {
            $this->getLogger()->error('upload error:'.$this->file['error']);
            $this->stateInfo = $this->getStateInfo( $file[ 'error' ] );
            throw new AppException($this->_fileErrorMap[$this->file['error']]);
        }
        if ( !is_uploaded_file( $file[ 'tmp_name' ] ) ) {
            $this->getLogger()->error('not found upload file:' . $file['tmp_name']);
            $this->stateInfo = $this->getStateInfo( "UNKNOWN" );
            throw new AppException(Constants::CODE_UPLOAD_UNCOMPLETED);
        }

        $this->oriName = $file[ 'name' ];
        $this->fileSize = $file[ 'size' ];
        $this->fileType = $this->getFileExt();
        if ( !$this->checkSize() ) {
            $this->getLogger()->error(sprintf('%s over limit size :%s' ,$this->fileSize, $this->config['maxSize']));
            $this->stateInfo = $this->getStateInfo( "SIZE" );
            throw new AppException(Constants::CODE_UPLOAD_OVER_LIMIT_SIZE);
        }
        if ( !$this->checkType() ) {
            $this->stateInfo = $this->getStateInfo( "TYPE" );
            throw new AppException(Constants::CODE_UPLOAD_ILLEGAL_TYPE);
        }

        // if is image , save the width and height info
        if( $this->isImage() ){
            $imgInfo = getimagesize( $file[ "tmp_name" ] );
            if( $imgInfo !== false ) {
                $this->width = $imgInfo[0];
                $this->height = $imgInfo[1];
            }else{
                throw new AppException(Constants::CODE_UPLOAD_ILLEGAL_TYPE);
            }
        }
        $fullPathName = $this->getFolder() . '/' . $this->getName();
        $this->fullName = str_replace( ROOT_DIR . '/' . $this->config["savePath"], "", $fullPathName );
        if ( $this->stateInfo == $this->stateMap[ 0 ] ) {
            if ( !move_uploaded_file( $file[ "tmp_name" ] , $fullPathName ) ) {
                $this->stateInfo = $this->getStateInfo( "MOVE" );
                $this->getLogger()->error(sprintf('move upload file %s to %s failed',$file['tmp_name'],$fullPathName));
                throw new AppException(Constants::CODE_UPLOAD_FAILED);
            }
        }
    }

    /**
     * 处理base64编码的图片上传
     * TODO ... 需要修改文件的名字， 带上宽高信息
     * @param $base64Data
     * @return mixed
     */
    private function base64ToImage( $base64Data )
    {
        $img = base64_decode( $base64Data );
        $this->fileName = time() . rand( 1 , 10000 ) . ".png";
        $this->fullName = $this->getFolder() . '/' . $this->fileName;
        if ( !file_put_contents( $this->fullName , $img ) ) {
            throw new AppException(Constants::CODE_UPLOAD_IO);
        }
        $this->oriName = "";
        $this->fileSize = strlen( $img );
        $this->fileType = ".png";
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    public function getFileInfo()
    {
        return array(
            "originalName" => $this->oriName ,
            "name" => $this->fileName ,
            "url" => $this->fullName ,
            "size" => $this->fileSize ,
            "width" => $this->width ,
            "height" => $this->height ,
            "type" => $this->fileType ,
            "state" => $this->stateInfo
        );
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private function getStateInfo( $errCode )
    {
        return !$this->stateMap[ $errCode ] ? $this->stateMap[ "UNKNOWN" ] : $this->stateMap[ $errCode ];
    }

    /**
     * 重命名文件
     * @return string
     */
    private function getName()
    {
        return $this->fileName = time() . rand( 1 , 10000 )
            . ( $this->isImage() ? '_' . $this->width . '-' . $this->height : '' )
            . $this->getFileExt();
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private function checkType()
    {
        $fileExts = $this->config[ "allowFiles" ];
        if( is_string( $fileExts ) ){
            $fileExts = isset( $this->extMap[ $fileExts ] ) ? $this->extMap[ $fileExts ] : array() ;
        }
        return in_array( $this->getFileExt() , $fileExts );
    }

    private function isImage(){
        return in_array( $this->getFileExt() , $this->extMap[ "image" ] );
    }
    /**
     * 文件大小检测
     * @return bool
     */
    private function  checkSize()
    {
        return $this->fileSize <= ( $this->config[ "maxSize" ] * 1024 );
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private function getFileExt()
    {
        return strtolower( strrchr( $this->file[ "name" ] , '.' ) );
    }

    /**
     * 按照日期自动创建存储文件夹
     * @return string
     */
    private function getFolder()
    {
        $pathStr = ROOT_DIR . '/' . $this->config[ "savePath" ];
        if ( strrchr( $pathStr , "/" ) != "/" ) {
            $pathStr .= "/";
        }
        $pathStr .= date( "Ymd" );
        if ( !file_exists( $pathStr ) ) {
            if ( !mkdir( $pathStr , 0777 , true ) ) {
                $this->getLogger()->error('mkdir '.$pathStr.' failed!');
                return false;
            }
        }
        return $pathStr;
    }
}