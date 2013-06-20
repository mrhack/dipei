<?php
/**
 * User: wangfeng
 * Date: 13-6-1
 * Time: 下午12:26
 */
interface ModelConstants
{
    //sex
    const SEX_FEMALE=0;
    const SEX_MALE=1;
    const SEX_UNKNOWN=2;

    //dipei type
    const LEPEI_PROFESSIONAL=11;
    const LEPEI_STUDENT=12;
    const LEPEI_HOST=13;
    const LEPEI_OVERSEAS=14;

    //gen lang start
const LANG_CODE_UK=21;
const LANG_CODE_TR=22;
const LANG_CODE_DA=23;
const LANG_CODE_ES=24;
const LANG_CODE_RU=25;
const LANG_CODE_NO=26;
const LANG_CODE_LV=27;
const LANG_CODE_FR=28;
const LANG_CODE_RO=29;
const LANG_CODE_VI=30;
const LANG_CODE_ZH_TW=31;
const LANG_CODE_ZH_CN=32;
const LANG_CODE_ID=33;
const LANG_CODE_SR=34;
const LANG_CODE_TH=35;
const LANG_CODE_ET=36;
const LANG_CODE_TL=37;
const LANG_CODE_KO=38;
const LANG_CODE_FI=39;
const LANG_CODE_LT=40;
const LANG_CODE_HR=41;
const LANG_CODE_DE=42;
const LANG_CODE_CS=43;
const LANG_CODE_PT_PT=44;
const LANG_CODE_IS=45;
const LANG_CODE_SL=46;
const LANG_CODE_MS=47;
const LANG_CODE_EN_US=48;
const LANG_CODE_JA=49;
const LANG_CODE_BG=50;
const LANG_CODE_SV=51;
const LANG_CODE_IT=52;
const LANG_CODE_HE=53;
const LANG_CODE_HU=54;
const LANG_CODE_CA=55;
const LANG_CODE_PL=56;
const LANG_CODE_NL=57;
const LANG_CODE_AR=58;
const LANG_CODE_PT_BR=59;
const LANG_CODE_SK=60;
const LANG_CODE_EL=61;
const LANG_CODE_EN_GB=62;

//gen lang end

    //lang familiar
    const LANG_FAMILIAR_NEW=81;
    const LANG_FAMILIAR_NORMAL=82;
    const LANG_FAMILIAR_FAMILIAR=83;


    //contact
    const CONTACT_TEL=91;
    const CONTACT_QQ=92;
    const CONTACT_WEIXIN=93;
    const CONTACT_EMAIL=94;


    //travel theme
    const THEME_GRADUATION=201;
    const THEME_HONEY_MOON=202;
    const THEME_ON_FOOT=203;
    const THEME_SHOPPING=204;
    const THEME_FOOD=205;
    const THEME_EXPLORE=206;
    const THEME_GROUP=207;

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
    const SCHEMA_OBJECT = 'object';
    const SCHEMA_ARRAY = 'array';
}