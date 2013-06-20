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

/**
 */
$words=array(
    //sex
    Constants::SEX_MALE=>'男',
    Constants::SEX_FEMALE=>'女',
    Constants::SEX_UNKNOWN=>'未知',

    //lepei_type
    Constants::LEPEI_PROFESSIONAL=>'专业地陪',
    Constants::LEPEI_STUDENT=>'留学生',
    Constants::LEPEI_HOST=>'当地居民',
    Constants::LEPEI_OVERSEAS=>'侨民'

    //
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
