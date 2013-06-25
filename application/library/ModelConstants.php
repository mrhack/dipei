<?php
/**
 * User: wangfeng
 * Date: 13-6-1
 * Time: 下午12:26
 */
interface ModelConstants
{
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
    const  LANGUAGE_CHINESE=21;
    const  LANGUAGE_CHINESE_TW=22;
    const  LANGUAGE_EN=23;
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