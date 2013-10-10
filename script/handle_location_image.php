<?php
/**
 * User: wangfeng
 * Date: 13-7-8
 * Time: 下午10:10
 */
//crop w-125 h-55
require_once __DIR__ . '/Bootstrap.php';

$helpMsg=<<<HELP
read location_spider collection and convert images
handle_location_image -dir your_dir

HELP;


//$baseDir='/Volumes/wpp/travel_spider2/datas';
$baseDir=getArgValue('dir','',$helpMsg,ArgValidator::newInstance()->setCheckDir());

class Location_SpiderModel extends LocationModel
{
    public function  getCollectionName()
    {
        return 'location_spider';
    }

    public static function getInstance()
    {
        return new Location_SpiderModel();
    }
}

$locations = Location_SpiderModel::getInstance()->fetch(array(), array('ims' => true));
mkdir(ROOT_DIR . '/public/img/1000',0777,true);
getLogger(__FILE__)->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
//$baseDir=ROOT_DIR .'/public/img';
foreach($locations as $location){
    if(strpos($location['ims'][0],'/1000/')===0){
        getLogger(__FILE__)->info('skip handled '.$location['_id']);
        continue;
    }
    $realPath = $baseDir . '/' . $location['ims'][0];
    $updateLocation=array(
        '_id'=>$location['_id'],
        'ims'=>array()
    );
    try{
        if(!file_exists($realPath)){
            getLogger(__FILE__)->error("$realPath not exists");
        } else{
            $updateLocation['ims']=array('/1000/'.$location['_id']);
            $imagic = new Imagick($realPath);
            $w=$imagic->getimagewidth();
            $h=$imagic->getimageheight();
            $updateLocation['ims'][0] .= "_$w-$h.jpg";
            $outputPath=ROOT_DIR.'/public/img'.$updateLocation['ims'][0];
            if(!file_exists($outputPath)){
                $imagic->cropImage($w - 125, $h - 55,0,0);
                $imagic->writeimage($outputPath);
                getLogger(__FILE__)->info("write $outputPath ok");
            }else{
                getLogger(__FILE__)->info('skip '.$outputPath);
            }
            unset($imagic);
        }
    }catch(Exception $ex){
        getLogger(__FILE__)->error('catch exception '.get_class($ex).':'.$ex->getMessage());
    }

    Location_SpiderModel::getInstance()->update($updateLocation);
    getLogger(__FILE__)->info("update {$location['_id']} image from {$location['ims'][0]} to {$updateLocation['ims'][0]}");
}