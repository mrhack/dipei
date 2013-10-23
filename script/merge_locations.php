<?php
/**
 * User: wangfeng
 * Date: 13-9-22
 * Time: 下午9:03
 */
require_once __DIR__ . '/Bootstrap.php';
$helpMsg=<<<HELP
merge location from A to B
merge_location -src location_spider -dst location

HELP;

$src = getArgValue('src', '', $helpMsg, ArgValidator::newInstance()->setRegExp('/^location\S*/'));
$dst = getArgValue('dst', '', $helpMsg, ArgValidator::newInstance()->setRegExp('/^location\S*/'));
if($src == $dst){
    echo 'src can not equal dst';
    exit(1);
}

$locationCollection1 = AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectCollection(Constants::$DB_LEPEI,$dst);
$locationCollection2 = AppMongo::getInstance(Constants::$CONN_MONGO_STRING)->selectCollection(Constants::$DB_LEPEI,$src);
$locationCollection1->ensureIndex(array('sid'=>1),array('unique'=>true,'dropDups'=>true));

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
        $loc['_id']=$id+1;
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
        try{
            $locationCollection1->insert($loc);
            $id++;
            getLogger(__FILE__)->log('info','add loc '.$loc['sid']);
        }catch (Exception $ex){
            getLogger(__FILE__)->error('insert '.$loc['n'].' catch error:'.$ex->getMessage());
        }
    }
}

getLogger(__FILE__)->log('info','merge complete');
