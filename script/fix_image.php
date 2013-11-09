<?php
/**
 * User: wangfeng
 * Date: 13-11-9
 * Time: 下午9:05
 */
require_once __DIR__.'/Bootstrap.php';

$imageDir = realpath(__DIR__.'/../public/img/1000');
$locationModel=LocationModel::getInstance();
$locations = $locationModel->fetch();
foreach($locations as $location){
    $files=glob($imageDir.'/'.$location['_id'].'*1111.jpg');
    if(empty($location['ims']) || strpos($location['ims'][0],'/1000/') !==0){
        getLogger(__FILE__)->info("reset {$location['_id']} image");
        $location['ims']=array();
        $files=glob($imageDir.'/'.$location['_id'].'*.jpg');
        foreach($files as $file){
            $location['ims'][] = '/1000/'.basename($file);
            getLogger(__FILE__)->info('set image ' . $location['ims'][0]);
            break;
        }
        $locationModel->update($location);
    }
}
