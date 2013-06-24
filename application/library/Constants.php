<?php
/**
 * 存放所有的静态变量
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:22
 */

abstract class Constants implements ErrorConstants,ModelConstants,LangConstants
{
    const CONN_MONGO_STRING='mongodb://127.0.0.1:27017/lepei?w=1';
    const DB_LEPEI = 'lepei';

    const PATH_LOG = '/data/logs/lepei';


    //gen by gen_lang
    public static $LANGS=array(self::LANGUAGE_CHINESE,self::LANGUAGE_CHINESE_TW,self::LANGUAGE_EN);
    public static $LANGS_FAMILIAR = array(self::LANGUAGE_FAMILIAR_BEGINNER,self::LANGUAGE_FAMILIAR_INTERMEDIATE,self::LANGUAGE_FAMILIAR_FREQUENT);
    public static $LOCALS = array('en_GB' => 'English(UK)','en_US' => 'English(US)','de' => 'Deutsch','nl' => 'Nederlands','fr' => 'Français','es' => 'Español','ca' => 'Català','it' => 'Italiano','pt_PT' => 'Português(PT)','pt_BR' => 'Português(BR)','no' => 'Norsk','fi' => 'Suomi','sv' => 'Svenska','da' => 'Dansk','cs' => 'Čeština','hu' => 'Magyar','ro' => 'Română','ja' => '日本語','zh_CN' => '简体中文','zh_TW' => '繁體中文','pl' => 'Polski','el' => 'Ελληνικά','ru' => 'Русский','tr' => 'Türkçe','bg' => 'Български','ar' => 'عربي','ko' => '한국어','he' => 'עברית','lv' => 'Latviski','uk' => 'Українська','id' => 'BahasaIndonesia','ms' => 'BahasaMalaysia','th' => 'ภาษาไทย','et' => 'Eesti','hr' => 'Hrvatski','lt' => 'Lietuvių','sk' => 'Slovenčina','sr' => 'Srpski','sl' => 'Slovenščina','vi' => 'TiếngViệt','tl' => 'Filipino','is' => 'Íslenska',) ;
    public static $MONEYS = array(
        "CNY" => array("symbol" => "CNY" , "desc" => 41),
        "USD" => array("symbol" => "US$" , "desc" => 42)
        );
    public static $CONTACTS = array(self::CONTACT_TEL,self::CONTACT_QQ,self::CONTACT_WEIXIN,self::CONTACT_EMAIL);
    public static $LEPEI_TYPES=array(
        self::LEPEI_PROFESSIONAL,
        self::LEPEI_STUDENT,
        self::LEPEI_HOST,
        self::LEPEI_OVERSEAS,
        );
    public static $TRAVEL_SERVICES=array();
    public static $TRAVEL_THEMES=array();

}
