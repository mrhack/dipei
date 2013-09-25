<?php
/**
 * User: wangfeng
 * Date: 13-9-24
 * Time: 下午8:32
 */
require_once __DIR__.'/Bootstrap.php';

$locationModel=LocationModel::getInstance();
$userModel=UserModel::getInstance();

foreach($locationModel->fetch() as $location){
    $locationModel->update(array('$unset'=>array('c.d'=>true)),array('_id'=>$location['_id']));
}

foreach($userModel->fetch(array('l_t'=>array('$exists'=>true))) as $userInfo){
    $align=1;
    $updateLocations=array();
    if(isset($userInfo['lid'])){
        $locationModel=LocationModel::getInstance();
        $updateLocations[$userInfo['lid']]['$inc']['c.d'] +=1*$align;
        $location=$locationModel->fetchOne(array('_id' => $userInfo['lid']),array('pt'=>true));
        if(!empty($location)){
            foreach($location['pt'] as $lid){
                $updateLocations[$lid]['$inc']['c.d']+=1 * $align;
            }
        }
    }
    foreach($updateLocations as $lid=>$updateLocation){
        $locationModel->update(
            $updateLocations[$lid],
            array('_id'=>$lid)
        );
    }
}

