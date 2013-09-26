<?php
/**
 * User: wangfeng
 * Date: 13-6-1
 * Time: 下午12:26
 */
interface ModelConstants
{
    const INDEX_MODE_ARRAY=0;
    const INDEX_MODE_ID=1;
    //sex
    const SEX_FEMALE=1;
    const SEX_MALE=2;
    const SEX_UNKNOWN=3;

    //dipei type
    const LEPEI_PROFESSIONAL=11;
    const LEPEI_STUDENT=12;
    const LEPEI_HOST=13;
    const LEPEI_OVERSEAS=14;

    //gen lang start
    const LANGUAGE_CHINESE=21;
//    const LANGUAGE_CHINESE_TW=22;
//    const LANGUAGE_EN=23;
    const LANGUAGE_English=31;
    const LANGUAGE_French=32;
    const LANGUAGE_German=33;
    const LANGUAGE_Spanish=34;
    const LANGUAGE_Italian=35;
    const LANGUAGE_Portuguese=36;
    const LANGUAGE_Esperanto=37;
    const LANGUAGE_Greek=38;
    const LANGUAGE_Dutch=39;
    const LANGUAGE_Finnish=40;
    const LANGUAGE_Swedish=41;
    const LANGUAGE_Danish=42;
    const LANGUAGE_Japanese=43;
    const LANGUAGE_Korean=44;
    const LANGUAGE_Mongolian=45;
    const LANGUAGE_Vietnamese=46;
    const LANGUAGE_Laotian=47;
    const LANGUAGE_Cambodian=48;
    const LANGUAGE_Thai=49;
    const LANGUAGE_Malay=50;
    const LANGUAGE_Indonesian=51;
    const LANGUAGE_Filipino=52;
    const LANGUAGE_Burmese=53;
    const LANGUAGE_Nepali=54;
    const LANGUAGE_Hindi=55;
    const LANGUAGE_Urdu=56;
    const LANGUAGE_Tamil=57;
    const LANGUAGE_Sinhala=58;
    const LANGUAGE_Bengali=59;
    const LANGUAGE_Turkish=60;
    const LANGUAGE_Persian=61;
    const LANGUAGE_Pashto=62;
    const LANGUAGE_Arabic=63;
    const LANGUAGE_Hausa=64;
    const LANGUAGE_Swahili=65;
    const LANGUAGE_Hebrew=66;
    const LANGUAGE_Russian=67;
    const LANGUAGE_Czech=68;
    const LANGUAGE_Serbian=69;
    const LANGUAGE_Romanian=70;
    const LANGUAGE_Albanian=71;
    const LANGUAGE_Bulgarian=72;
    const LANGUAGE_Hungarian=73;
    const LANGUAGE_Polish=74;
    const LANGUAGE_Croatian=75;
    const LANGUAGE_Ukrainian=76;
    const LANGUAGE_Norwegian=77;
    const LANGUAGE_Lithuanian=78;
    const LANGUAGE_Estonian=79;
    const LANGUAGE_Icelandic=80;

//gen lang end

    //lang familiar
    const FAMILIAR_BEGINNER=81;
    const FAMILIAR_INTERMEDIATE=82;
    const FAMILIAR_FREQUENT=83;


    //contact
    const CONTACT_TEL=91;
    const CONTACT_QQ=92;
    const CONTACT_WEIXIN=93;
    const CONTACT_EMAIL=94;


    //money
    const MONEY_CNY=121;
    const MONEY_SGD=122;
    const MONEY_HKD=123;
    const MONEY_USD=124;
    const MONEY_GBP=125;
    const MONEY_DKK=126;
    const MONEY_UAH=127;
    const MONEY_ILS=128;
    const MONEY_RUB=129;
    const MONEY_BGN=130;
    const MONEY_CAD=131;
    const MONEY_HUF=132;
    const MONEY_ZAR=133;
    const MONEY_QAR=134;
    const MONEY_IDR=135;
    const MONEY_INR=136;
    const MONEY_KZT=137;
    const MONEY_COP=138;
    const MONEY_EGP=139;
    const MONEY_MXN=140;
    const MONEY_VEF=141;
    const MONEY_BHD=142;
    const MONEY_BRL=143;
    const MONEY_LVL=144;
    const MONEY_NOK=145;
    const MONEY_CZK=146;
    const MONEY_MDL=147;
    const MONEY_FJD=148;
    const MONEY_TWD=149;
    const MONEY_TRY=150;
    const MONEY_NZD=151;
    const MONEY_JPY=152;
    const MONEY_CLP=153;
    const MONEY_GEL=154;
    const MONEY_EUR=155;
    const MONEY_SAR=156;
    const MONEY_PLN=157;
    const MONEY_THB=158;
    const MONEY_AUD=159;
    const MONEY_SEK=160;
    const MONEY_CHF=161;
    const MONEY_KWD=162;
    const MONEY_LTL=163;
    const MONEY_JOD=164;
    const MONEY_NAD=165;
    const MONEY_RON=166;
    const MONEY_XOF=167;
    const MONEY_AZN=168;
    const MONEY_OMR=169;
    const MONEY_ARS=170;
    const MONEY_AED=171;
    const MONEY_KRW=172;
    const MONEY_MYR=173;


    //travel theme

//    const THEME_GRADUATION=201;
//    const THEME_HONEY_MOON=202;
//    const THEME_ON_FOOT=203;
//    const THEME_EXPLORE=206;
//    const THEME_GROUP=207;

    //new them
    const THEME_HISTORY=210;
    const THEME_SCENE=211;
    const THEME_FOOD=205;
    const THEME_SPORT=212;
    const THEME_ENTERTAINMENT=213;
    const THEME_SHOPPING=204;
    const THEME_CULTURE=214;
    const THEME_CITY=215;

    //travel services
    const SERVICE_CAR=301;
    const SERVICE_HOTEL=302;
    const SERVICE_INTRODUCTION=303;
    const SERVICE_SHOPPING_GUIDE=304;
    const SERVICE_FOOD_GUIDE=305;
    const SERVICE_IN_OUT=306;//接送
    const SERVICE_TRANSLATION=307;
    const SERVICE_SCENE_INTRODUCTION=308;
    const SERVICE_ORDER_CAR=309;
    const SERVICE_ORDER_HOTEL=310;

    const SCHEMA_STRING = 'str';
    const SCHEMA_INT = 'int';
    const SCHEMA_DATE = 'date';
    const SCHEMA_DOUBLE = 'double';
    const SCHEMA_OBJECT = 'object';
    const SCHEMA_ARRAY = 'array';


    const LIKE_LOCATION = 1;
    const LIKE_PROJECT=2;
    const LIKE_USER=3;
    const LIKE_POST=4;

    const STATUS_NEW=0;
    const STATUS_PASSED=10;
    const STATUS_DELETE=-1;

    const FEED_TYPE_POST=1;
    const FEED_TYPE_QA=2;
    const FEED_TYPE_PROJECT=3;

    const VUID_SYSTEM=-1;
}