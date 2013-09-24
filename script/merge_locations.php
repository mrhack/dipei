<?php
/**
 * User: wangfeng
 * Date: 13-9-22
 * Time: 下午9:03
 */
require_once __DIR__ . '/Bootstrap.php';
$locationCollection1 = AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectCollection(Constants::$DB_LEPEI,'location_t1');
$locationCollection2 = AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectCollection(Constants::$DB_LEPEI,'location_t2');

$c1=$locationCollection1->find(array('ptc'=>1));
$c2=$locationCollection2->find()->sort(array('ptc'=>1));

$id=$locationCollection1->count();

foreach($c1 as $loc){
    $locations1[$loc['sid']]=$loc;
}


foreach($c2 as $loc){
    $locations2[$loc['_id']]=$loc;
}

//merge 2 location
foreach($locations2 as $loc){
    if(!isset($locations1[$loc['sid']])){
        $id++;
        $loc['_id']=$id;
        $locations1[$loc['sid']]=$loc;
        foreach($loc['pt'] as $i=>$lid){
            $sid=$locations2[$lid]['sid'];
            $convertLid=$locations1[$sid]['_id'];
            if($convertLid>0){
                $loc['pt'][$i]=$convertLid;
            }else{
                getLogger(__FILE__)->warn('not find sid '.$sid);
            }
        }
        $locationCollection1->insert($loc);
        getLogger(__FILE__)->log('info','add loc '.$loc['sid']);
    }
}

getLogger(__FILE__)->log('info','merge complete');
