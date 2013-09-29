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
    $changed=($translateRecord[Constants::LANG_ZH_CN] !== $word);
    $translateRecord[Constants::LANG_ZH_CN]=$word;
    foreach($translateTasks as $lang){
        if(!isset($translateRecord[$lang]) || $changed){
            $translateRecord[$lang] = $translator->translate(Constants::LANG_ZH_CN, $lang, $word);
            echo "translate $word from zh_cn to $lang " . $translateRecord[$lang], "\n";
            $needUpdate=true;
        }
    }
    if($needUpdate){
        $translationModel->save($translateRecord);
//        $translationModel->update(array(Constants::LANG_ZH_CN => $word), $translateRecord,array('upsert'=>true));
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
    Constants::SEX_MALE=>'先生',
    Constants::SEX_FEMALE=>'女士',
    Constants::SEX_UNKNOWN=>'未知',

    //lepei_type
    Constants::LEPEI_PROFESSIONAL=>'专业地陪',
    Constants::LEPEI_STUDENT=>'留学生',
    Constants::LEPEI_HOST=>'当地居民',
    Constants::LEPEI_OVERSEAS=>'侨民',

    //language type
    Constants::LANGUAGE_CHINESE=>'汉语(普通话)',
//    Constants::LANGUAGE_CHINESE_TW=>'汉语(粤语)',
//    Constants::LANGUAGE_EN=>'英语',
    Constants::LANGUAGE_English=>	"英语",
    Constants::LANGUAGE_French=>	"法语",
    Constants::LANGUAGE_German=>	"德语",
    Constants::LANGUAGE_Spanish=>	"西班牙语",
    Constants::LANGUAGE_Italian=>	"意大利语",
    Constants::LANGUAGE_Portuguese=>	"葡萄牙语",
    Constants::LANGUAGE_Esperanto=>	"世界语",
    Constants::LANGUAGE_Greek=>	"希腊语",
    Constants::LANGUAGE_Dutch=>	"荷兰语",
    Constants::LANGUAGE_Finnish=>	"芬兰语",
    Constants::LANGUAGE_Swedish=>	"瑞典语",
    Constants::LANGUAGE_Danish=>	"丹麦语",
    Constants::LANGUAGE_Japanese=>	"日语",
    Constants::LANGUAGE_Korean=>	"韩语",
    Constants::LANGUAGE_Mongolian=>	"蒙语",
    Constants::LANGUAGE_Vietnamese=>	"越南语",
    Constants::LANGUAGE_Laotian=>	"老挝语",
    Constants::LANGUAGE_Cambodian=>	"柬埔寨语",
    Constants::LANGUAGE_Thai=>	"泰语",
    Constants::LANGUAGE_Malay=>	"马来语",
    Constants::LANGUAGE_Indonesian=>	"印尼语",
    Constants::LANGUAGE_Filipino=>	"菲律宾语",
    Constants::LANGUAGE_Burmese=>	"缅甸语",
    Constants::LANGUAGE_Nepali=>	"尼泊尔语",
    Constants::LANGUAGE_Hindi=>	"印地语",
    Constants::LANGUAGE_Urdu=>	"乌尔都语",
    Constants::LANGUAGE_Tamil=>	"泰米尔语",
    Constants::LANGUAGE_Sinhala=>	"僧伽罗语",
    Constants::LANGUAGE_Bengali=>	"孟加拉语",
    Constants::LANGUAGE_Turkish=>	"土耳其语",
    Constants::LANGUAGE_Persian=>	"波斯语",
    Constants::LANGUAGE_Pashto=>	"普什图语",
    Constants::LANGUAGE_Arabic=>	"阿拉伯语",
    Constants::LANGUAGE_Hausa=>	"豪萨语",
    Constants::LANGUAGE_Swahili=>	"斯瓦希里语",
    Constants::LANGUAGE_Hebrew=>	"希伯莱语",
    Constants::LANGUAGE_Russian=>	"俄语",
    Constants::LANGUAGE_Czech=>	"捷克语",
    Constants::LANGUAGE_Serbian=>	"塞尔维亚语",
    Constants::LANGUAGE_Romanian=>	"罗马尼亚语",
    Constants::LANGUAGE_Albanian=>	"阿尔巴尼亚语",
    Constants::LANGUAGE_Bulgarian=>	"保加利亚语",
    Constants::LANGUAGE_Hungarian=>	"匈牙利语",
    Constants::LANGUAGE_Polish=>	"波兰语",
    Constants::LANGUAGE_Croatian=>	"克罗地亚语",
    Constants::LANGUAGE_Ukrainian=>	"乌克兰语",
    Constants::LANGUAGE_Norwegian=>	"挪威语",
    Constants::LANGUAGE_Lithuanian=>	"立陶宛语",
    Constants::LANGUAGE_Estonian=>	"爱沙尼亚语",
    Constants::LANGUAGE_Icelandic=>	"冰岛语",


    //language familiar
    Constants::FAMILIAR_BEGINNER => '初学者',
    Constants::FAMILIAR_INTERMEDIATE=>'中级',
    Constants::FAMILIAR_FREQUENT=>'流利',

    //contacts
    Constants::CONTACT_EMAIL=>'Email',
    Constants::CONTACT_QQ=>'QQ',
    Constants::CONTACT_TEL=>'电话',
    Constants::CONTACT_WEIXIN=>'微信',


    //money
    Constants::MONEY_CNY=>'人民币',
    Constants::MONEY_SGD=>'新元',
    Constants::MONEY_HKD=>'港币',
    Constants::MONEY_USD=>'美元',
    Constants::MONEY_GBP=>'英镑',
    Constants::MONEY_DKK=>'丹麦克朗',
    Constants::MONEY_UAH=>'乌克兰赫夫米',
    Constants::MONEY_ILS=>'以色列新谢克尔',
    Constants::MONEY_RUB=>'俄罗斯卢布',
    Constants::MONEY_BGN=>'保加利亚新列弗',
    Constants::MONEY_CAD=>'加元',
    Constants::MONEY_HUF=>'匈牙利福林',
    Constants::MONEY_ZAR=>'南非兰特',
    Constants::MONEY_QAR=>'卡塔尔里亚尔',
    Constants::MONEY_IDR=>'印尼卢比',
    Constants::MONEY_INR=>'印度卢比',
    Constants::MONEY_KZT=>'哈萨克斯坦坚戈',
    Constants::MONEY_COP=>'哥伦比亚比索',
    Constants::MONEY_EGP=>'埃及镑',
    Constants::MONEY_MXN=>'墨西哥比索',
    Constants::MONEY_VEF=>'委内瑞拉玻利瓦尔',
    Constants::MONEY_BHD=>'巴林第纳尔',
    Constants::MONEY_BRL=>'巴西雷阿尔',
    Constants::MONEY_LVL=>'拉脱维亚拉特',
    Constants::MONEY_NOK=>'挪威克朗',
    Constants::MONEY_CZK=>'捷克克朗',
    Constants::MONEY_MDL=>'摩尔多瓦列伊',
    Constants::MONEY_FJD=>'斐济元',
    Constants::MONEY_TWD=>'新台币',
    Constants::MONEY_TRY=>'新土耳其里拉',
    Constants::MONEY_NZD=>'新西兰元',
    Constants::MONEY_JPY=>'日元',
    Constants::MONEY_CLP=>'智利比索',
    Constants::MONEY_GEL=>'格鲁吉亚拉里',
    Constants::MONEY_EUR=>'欧元',
    Constants::MONEY_SAR=>'沙特阿拉伯里亚尔',
    Constants::MONEY_PLN=>'波兰兹罗提',
    Constants::MONEY_THB=>'泰铢',
    Constants::MONEY_AUD=>'澳元',
    Constants::MONEY_SEK=>'瑞典克朗',
    Constants::MONEY_CHF=>'瑞士法郎',
    Constants::MONEY_KWD=>'科威特第纳尔',
    Constants::MONEY_LTL=>'立陶宛利塔斯',
    Constants::MONEY_JOD=>'约旦第纳尔',
    Constants::MONEY_NAD=>'纳米比亚元',
    Constants::MONEY_RON=>'罗马尼亚新列伊',
    Constants::MONEY_XOF=>'西非法郎',
    Constants::MONEY_AZN=>'阿塞拜疆新马纳特',
    Constants::MONEY_OMR=>'阿曼里亚尔',
    Constants::MONEY_ARS=>'阿根廷比索',
    Constants::MONEY_AED=>'阿联酋迪拉姆',
    Constants::MONEY_KRW=>'韩元',
    Constants::MONEY_MYR=>'马来西亚令吉',

    //travel themes
//    Constants::THEME_EXPLORE=>'探险',
//    Constants::THEME_HONEY_MOON=>'蜜月',
//    Constants::THEME_ON_FOOT=>'徒步',
//    Constants::THEME_GRADUATION=>'毕业',
//    Constants::THEME_GROUP=>'团队建设',

    //new theme
    Constants::THEME_HISTORY=>'文物古迹',
    Constants::THEME_SCENE=>'自然风光',
    Constants::THEME_FOOD=>'美食',
    Constants::THEME_SPORT=>'运动',
    Constants::THEME_ENTERTAINMENT=>'休闲',
    Constants::THEME_SHOPPING=>'购物',
    Constants::THEME_CULTURE=>'文化艺术',
    Constants::THEME_CITY=>'城市观光',

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
