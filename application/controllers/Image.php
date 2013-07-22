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
        if($this->getRequest()->getActionName() == 'avatar'){
            if(empty($this->user)){
                throw new AppException(Constants::CODE_NO_PERM, 'not logined');
            }
        }
        return true;
    }

    public function uploadAction()
    {
        $uploader = new AppUploader('upFile');
        $uploader->upFile();
        $this->render_ajax(Constants::CODE_SUCCESS,'',$uploader->getFileInfo());
        return false;
    }

    public function uploadForEditorAction()
    {
        $uploader = new AppUploader('upFile');
        $uploader->upFile();
        $info = $uploader->getFileInfo();
        echo "{'url':'" . $info["url"] . "','title':'','original':'" . $info["originalName"] . "','state':'" . $info["state"] . "'}";
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
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    private function ensureCache($path)
    {
        if(file_exists($path)){
            header('Last-Modified:'.gmdate('D,d M Y H:i:s',filemtime($path)).' GMT');
            header('Etag:"' . md5($path).'"');
            $lastTime=filemtime($path);
            $ifModifiedSince=$this->getRequest()->getEnv('If-Modified-Since');
//            var_dump($ifModifiedSince,$_ENV);exit;
            if($lastTime == $ifModifiedSince){
                header('HTTP/1.0 304 Not Modified');
                return true;
            }
        }
        return false;
    }

    public function thumbAction($basePath,$sWidth,$sHeight,$suffix)
    {
        $imgUploadFolder=ROOT_DIR.Yaf_Application::app()->getConfig()->get('application')['imgUploadFolder'];
        $originPath=sprintf('%s/%s.%s',$imgUploadFolder,$basePath,$suffix);

        $cacheFolder = '/tmp';
        $outPath=sprintf('%s/%s_%s-%s.%s',$cacheFolder,$basePath,$sWidth,$sHeight,$suffix);
        if(!file_exists($outPath)){
            mkdir(dirname($outPath), 0777, true);
        }
        $sWidth = min(1024, $sWidth);
        $sHeight = min(768, $sHeight);
        if($this->ensureCache($outPath)){
            return false;//cached
        }
        if(file_exists($outPath)){//cached
            header('Content-type: image/jpeg');
            $imagick = new Imagick($outPath);
            echo $imagick;
        }else if(file_exists($originPath)){//do scale
            $imagick = new Imagick($originPath);
            header('Content-type: image/jpeg');
            $imagick->thumbnailimage($sWidth, $sHeight);
            $imagick->writeimage($outPath);
            echo $imagick;
        }else{
            //TODO show default image?
            echo "not found";
        }
        return false;
    }
}