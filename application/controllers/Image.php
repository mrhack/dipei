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

    public function thumbAction($basePath,$sWidth,$sHeight,$suffix)
    {
        $imgUploadFolder=ROOT_DIR.Yaf_Application::app()->getConfig()->get('application')['imgUploadFolder'];
        $originPath=sprintf('%s/%s.%s',$imgUploadFolder,$basePath,$suffix);

        $cacheFolder = '/tmp';
        $outPath=sprintf('%s/%s_%s-%s.%s',$cacheFolder,$basePath,$sWidth,$sHeight,$suffix);
//        echo $originPath;
//        echo $outPath;
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