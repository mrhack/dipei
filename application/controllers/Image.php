<?php
/**
 * User: wangfeng
 * Date: 13-7-3
 * Time: 下午10:07
 */
class ImageController extends BaseController
{
    public function validateAuth()
    {
        return true;
    }

    public function uploadAction()
    {
        $uploader = new AppUploader('upFile');
        $uploader->upFile();
        $this->render_ajax(Constants::CODE_SUCCESS,'',$uploader->getFileInfo());
        return false;
    }

    public function cropAction()
    {
        $w=$this->getRequest()->getRequest('w',0);
        $h=$this->getRequest()->getRequest('h',0);
        $x = $this->getRequest()->getRequest('x', 0);
        $y = $this->getRequest()->getRequest('y', 0);
        if($x<0 || $y<0 || $w<=0 || $h<=0){
            throw new AppException(Constants::CODE_PARAM_INVALID);
        }

        $uploader = new AppUploader('upFile');
        $uploader->upFile();
        $file=$uploader->getFileInfo();
        $path = ROOT_DIR.$file['url'];
        $cropPath=preg_replace('/_(\d+)-(\d+)/', "_$w-$h",$path);
        try{
            $imagick=new Imagick($path);
            $imagick->cropImage($w, $h, $x,$y);
            $imagick->writeimage($cropPath);
            $file['url']=$cropPath;
//            $imagick->removeImage();
            $this->render_ajax(Constants::CODE_SUCCESS, '', $file);
            return false;
        }catch (Exception $ex){
            throw new AppException(Constants::CODE_UPLOAD_FAILED,'',array(),$ex);
        }
    }

    public function thumbAction($basePath,$sWidth,$sHeight,$suffix)
    {
        $imgUploadFolder=ROOT_DIR.Yaf_Application::app()->getConfig()->get('application')['imgUploadFolder'];
        $originPath=sprintf('%s/%s.%s',$imgUploadFolder,$basePath,$suffix);

        $cacheFolder = '/tmp';
        $outPath=sprintf('%s/%s_%s-%s.%s',$cacheFolder,$basePath,$sWidth,$sHeight,$suffix);
        mkdir(dirname($outPath), 0777, true);
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