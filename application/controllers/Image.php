<?php
/**
 * User: wangfeng
 * Date: 13-7-3
 * Time: 下午10:07
 * TODO add image 304
 */
class ImageController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'avatar'
            || $this->getRequest()->getActionName() == 'upload'
            || $this->getRequest()->getActionName() == 'uploadForEditor'
            || $this->getRequest()->getActionName() == 'uploadUserPhoto'){
            if(empty($this->user)){
                throw new AppException(Constants::CODE_NO_PERM, 'not logined');
            }
        }
        return true;
    }

    private function doUpload()
    {
        $uploader = new AppUploader('upFile');
        $uploader->upFile();
        return $uploader->getFileInfo();
    }

    public function uploadAction()
    {
        $this->render_ajax(Constants::CODE_SUCCESS, '', $this->doUpload());
        return false;
    }

    public function removeUserPhotoAction(){
        $photoName = $this->getRequest()->getRequest('pname','');
        $userInfo = &$this->user;
        if( !empty( $photoName ) ){
            $imgs = $userInfo['ims'];
            $newArr = array();
            
            foreach( $imgs as $img ) {
                if( $photoName != $img ){
                    $newArr[] = $img;
                }
            }
            $userInfo['ims'] = $newArr;
            // update user
            UserModel::getInstance()->updateUser( $userInfo );
            $this->render_ajax(Constants::CODE_SUCCESS);
        } else {
            $this->render_ajax(Constants::CODE_NOT_FOUND);
        }
        return false;
    }

    public function uploadUserPhotoAction()
    {
        $info=$this->doUpload();
        $this->user['ims'][] = $info['url'];
        UserModel::getInstance()->updateUser($this->user);
        $this->render_ajax(Constants::CODE_SUCCESS, '', $info);
        return false;
    }

    public function uploadForEditorAction()
    {
        $info = $this->doUpload();
        $result = array();
        $result['url'] = $info["url"];
        $result['title'] = $this->getRequest()->getRequest('pictitle','');
        $result['original'] = $info["originalName"];
        $result['state'] = $info["state"];
        echo json_encode( $result );
        return false;
    }

    public function cropAction()
    {
        $file=$this->doCrop();
        $this->render_ajax(Constants::CODE_SUCCESS, '', $file);
        return false;
    }

    private function doCrop()
    {
        $w=$this->getRequest()->getRequest('w',0);
        $h=$this->getRequest()->getRequest('h',0);
        $x = $this->getRequest()->getRequest('x', 0);
        $y = $this->getRequest()->getRequest('y', 0);
        $rate = $this->getRequest()->getRequest('rate', 0);
        $upFile=$this->getRequest()->getRequest('upFile','');
        if($x<0 || $y<0 || $w<=0 || $h<=0 || empty($upFile)){
            throw new AppException(Constants::CODE_PARAM_INVALID);
        }

        $file['url']=preg_replace('/_(\d+)-(\d+)/', "_$w-$h",$upFile);
        $file['width']=$w;$file['height']=$h;
        $path = ROOT_DIR . '/public/img' . $upFile;
        $cropPath = ROOT_DIR .'/public/img' . $file['url'];
        try{
            $imagick=new Imagick($path);
            $imagick->cropImage($w, $h, $x,$y);
            $imagick->writeimage($cropPath);
//            unlink($path);
            return $file;
        }catch (Exception $ex){
            throw new AppException(Constants::CODE_UPLOAD_FAILED,'',array(),$ex);
        }
    }

    public function avatarAction()
    {
        $file=$this->doCrop();
        $this->user['h'] = $file['url'];
        UserModel::getInstance()->update($this->user);
        $this->render_ajax(Constants::CODE_SUCCESS,'',$file);
        return false;
    }

    private function ensureCache($path)
    {
        if(file_exists($path)){
            header('Last-Modified:'.gmdate('D ,d M Y H:i:s',filemtime($path)).' GMT');
            header('Etag:"' . md5($path).'"');
            $lastTime=filemtime($path);
            $ifModifiedSince=$this->getRequest()->getEnv('If-Modified-Since');
            if($lastTime == $ifModifiedSince){
                header('HTTP/1.0 304 Not Modified');
                return true;
            }
        }
        return false;
    }

    public function thumbAction($basePath, $oWidth, $oHeight, $sWidth, $sHeight, $suffix)
    {
        $cWidth = $sWidth;
        $cHeight = $sHeight;
        if( $sWidth != 0 && $sHeight != 0 ){
            // get the perfect rate
            if( $oWidth / $sWidth > $oHeight / $sHeight ){
                $cHeight = ceil( $sWidth / $oWidth * $oHeight );
            } else {
                $cWidth = ceil( $sHeight / $oHeight * $oWidth );
            }
        }

        $imgUploadFolder=ROOT_DIR.Yaf_Application::app()->getConfig()->get('application')['imgUploadFolder'];
        $originPath=sprintf('%s/%s%s-%s.%s',$imgUploadFolder,$basePath,$oWidth,$oHeight,$suffix);

        $cacheFolder = '/tmp';
        $outPath=sprintf('%s/%s%s-%s_%s-%s.%s',$cacheFolder,$basePath,$oWidth,$oHeight,$sWidth,$sHeight,$suffix);
        if(!file_exists(dirname($outPath))){
            mkdir(dirname($outPath), 0777, true);
        }

        //$sWidth = min(1024, $sWidth);
        //$sHeight = min(768, $sHeight);
        if($this->ensureCache($outPath)){
            return false;//cached
        }
       
        if(file_exists($outPath)){//cached
            header('Content-type: image/jpeg');
            $imagick = new Imagick($outPath);
            echo $imagick;
        }else if(file_exists($originPath)){//do scale
            header('Content-type: image/jpeg');
            $imagick = new Imagick($originPath);
            if( $sWidth != 0 || $sHeight != 0 ){
                $imagick->thumbnailimage($cWidth, $cHeight);
                $imagick->writeimage($outPath);
            }
            echo $imagick;
        }else{
            //TODO show default image?
            echo "not found";
        }
        return false;
    }
}