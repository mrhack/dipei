<?php
/**
 * User: wangfeng
 * Date: 13-7-8
 * Time: 下午10:10
 */
//crop w-125 h-55
require_once __DIR__ . '/Bootstrap.php';
$locations = LocationModel::getInstance()->fetch(array(), array('ims' => true));
mkdir(ROOT_DIR . '/public/img/1000',0777,true);
getLogger(__FILE__)->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
foreach($locations as $location){
    $realPath = ROOT_DIR . '/public/img/' . $location['ims'][0];
    $updateLocation=array(
        '_id'=>$location['_id'],
        'ims'=>array()
    );
    try{
        if(!file_exists($realPath)){
            getLogger(__FILE__)->error("$realPath not exists");
        } else{
            $updateLocation['ims']=array('1000/'.$location['_id']);
            $imagic = new Imagick($realPath);
            $w=$imagic->getimagewidth();
            $h=$imagic->getimageheight();
            $updateLocation['ims'][0] .= "_$w-$h.jpg";
            $outputPath=ROOT_DIR.'/public/img/'.$updateLocation['ims'][0];

            $imagic->cropImage($w - 125, $h - 55,0,0);
            $imagic->writeimage($outputPath);
            getLogger(__FILE__)->info("write $outputPath ok");
            unset($imagic);
        }
    }catch(Exception $ex){
        getLogger(__FILE__)->error('catch exception '.get_class($ex).':'.$ex->getMessage());
    }

    LocationModel::getInstance()->update($updateLocation);
    getLogger(__FILE__)->info("update {$location['_id']} image from {$location['ims'][0]} to {$updateLocation['ims'][0]}");
}