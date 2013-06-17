<?php
/**
 * User: wangfeng
 * Date: 13-6-14
 * Time: 下午10:59
 */
require_once 'Bootstrap.php';

$translateTasks=array(
    Constants::LANG_EN,
    Constants::LANG_PY,
);


//男、女、未知
function doTranslation($word){

    global $translateTasks;
    $translationModel=TranslationModel::getInstance();
    $translator=AppTranslator::getInstance();

    $translateRecord = $translationModel->fetchOne(array(Constants::LANG_ZH_CN => $word));
    $translateRecord = $translateRecord?$translateRecord:array(Constants::LANG_ZH_CN=>$word);

    $needUpdate=false;
    foreach($translateTasks as $lang){
        if(!isset($translateRecord[$lang])){
            $translateRecord[$lang] = $translator->translate(Constants::LANG_ZH_CN, $lang, $word);
            echo "translate $word from zh_cn to $lang " . $translateRecord[$lang], "\n";
            $needUpdate=true;
        }
    }
    if($needUpdate){
        $translationModel->saveWord(array(Constants::LANG_ZH_CN => $word), $translateRecord);
        $translateRecord = $translationModel->fetchOne(array(Constants::LANG_ZH_CN=>$word));
        echo "save translate $word\n";
    }else{
        echo "skip save translate $word\n";
    }
    return $translateRecord;
}

$words=array(
    '男','女','未知',//sex
);

foreach($words as $word){
    doTranslation($word);
}

$locationModel=LocationModel::getInstance();
foreach($locationModel->fetch() as $location){
    $translateRecord=doTranslation($location['n']);
    if(!isset($location['nid'])){
        $location['nid'] = $translateRecord['_id'];
        $locationModel->update($location);
        echo "update location nid ".$translateRecord['_id'],"\n";
    }
}
