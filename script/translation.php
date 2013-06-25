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
function doTranslation($id,$word){

    global $translateTasks;
    $translationModel=TranslationModel::getInstance();
    $translator=AppTranslator::getInstance();

    $translateRecord = $translationModel->fetchOne(array('_id'=>$id));
    $translateRecord = $translateRecord?$translateRecord:array('_id'=>$id,Constants::LANG_ZH_CN=>$word);

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
    Constants::LEPEI_OVERSEAS=>'侨民',

    //language type
    Constants::LANGUAGE_CHINESE=>'汉语(普通话)',
    Constants::LANGUAGE_CHINESE_TW=>'汉语(粤语)',
    Constants::LANGUAGE_EN=>'英语',

    //language familiar
    Constants::FAMILIAR_BEGINNER => '初学者',
    Constants::FAMILIAR_INTERMEDIATE=>'中级',
    Constants::FAMILIAR_FREQUENT=>'流利',

    //contacts
    Constants::CONTACT_EMAIL=>'Email',
    Constants::CONTACT_QQ=>'QQ',
    Constants::CONTACT_TEL=>'电话',
    Constants::CONTACT_WEIXIN=>'微信',

    //TODO add money desc

    //travel themes
    Constants::THEME_EXPLORE=>'探险',
    Constants::THEME_HONEY_MOON=>'蜜月',
    Constants::THEME_ON_FOOT=>'徒步',
    Constants::THEME_SHOPPING=>'购物',
    Constants::THEME_FOOD=>'美食',
    Constants::THEME_GRADUATION=>'毕业',
    Constants::THEME_GROUP=>'团队建设',


    //travel services
    Constants::SERVICE_CAR=>'租车',
    Constants::SERVICE_HOTEL=>'住宿',
    Constants::SERVICE_INTRODUCTION=>'讲解',
    Constants::SERVICE_FOOD_GUIDE=>'美食推荐',
    Constants::SERVICE_SHOPPING_GUIDE=>'购物推荐',
    Constants::SERVICE_IN_OUT=>'接送',
    Constants::SERVICE_TRANSLATION=>'翻译',
    Constants::SERVICE_SCENE_INTRODUCTION=>'景点解说',
    Constants::SERVICE_ORDER_CAR=>'代订车辆',
    Constants::SERVICE_ORDER_HOTEL=>'代订酒店',

);

foreach($words as $id=>$word){
    doTranslation($id,$word);
}

$locationModel=LocationModel::getInstance();
foreach($locationModel->fetch() as $location){
    $translateRecord=doTranslation($location['_id']+1000,$location['n']);
    if(!isset($location['nid'])){
        $location['nid'] = $translateRecord['_id'];
        $locationModel->update($location);
        echo "update location nid ".$translateRecord['_id'],"\n";
    }
}
