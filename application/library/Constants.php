<?php
/**
 * 存放所有的静态变量
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:22
 */

abstract class Constants implements ErrorConstants, ModelConstants, LangConstants
{
    public static $CONN_MONGO_STRING = 'mongodb://127.0.0.1:27017/lepei?w=1';
    public static $DB_LEPEI = 'lepei';

    public static $PATH_LOG = '/data/logs/lepei';


    //gen by gen_lang
    public static $LANGS = array(self::LANG_PY, self::LANG_SQ, self::LANG_SQ_AL, self::LANG_AR, self::LANG_AR_DZ, self::LANG_AR_BH, self::LANG_AR_EG, self::LANG_AR_IQ, self::LANG_AR_JO, self::LANG_AR_KW, self::LANG_AR_LB, self::LANG_AR_LY, self::LANG_AR_MA, self::LANG_AR_OM, self::LANG_AR_QA, self::LANG_AR_SA, self::LANG_AR_SD, self::LANG_AR_SY, self::LANG_AR_TN, self::LANG_AR_AE, self::LANG_AR_YE, self::LANG_BE, self::LANG_BE_BY, self::LANG_BG, self::LANG_BG_BG, self::LANG_CA, self::LANG_CA_ES, self::LANG_ZH, self::LANG_ZH_CN, self::LANG_ZH_HK, self::LANG_ZH_SG, self::LANG_ZH_TW, self::LANG_HR, self::LANG_HR_HR, self::LANG_CS, self::LANG_CS_CZ, self::LANG_DA, self::LANG_DA_DK, self::LANG_NL, self::LANG_NL_BE, self::LANG_NL_NL, self::LANG_EN, self::LANG_EN_AU, self::LANG_EN_CA, self::LANG_EN_IN, self::LANG_EN_IE, self::LANG_EN_MT, self::LANG_EN_NZ, self::LANG_EN_PH, self::LANG_EN_SG, self::LANG_EN_ZA, self::LANG_EN_GB, self::LANG_EN_US, self::LANG_ET, self::LANG_ET_EE, self::LANG_FI, self::LANG_FI_FI, self::LANG_FR, self::LANG_FR_BE, self::LANG_FR_CA, self::LANG_FR_FR, self::LANG_FR_LU, self::LANG_FR_CH, self::LANG_DE, self::LANG_DE_AT, self::LANG_DE_DE, self::LANG_DE_LU, self::LANG_DE_CH, self::LANG_EL, self::LANG_EL_CY, self::LANG_EL_GR, self::LANG_IW, self::LANG_IW_IL, self::LANG_HI_IN, self::LANG_HU, self::LANG_HU_HU, self::LANG_IS, self::LANG_IS_IS, self::LANG_IN, self::LANG_IN_ID, self::LANG_GA, self::LANG_GA_IE, self::LANG_IT, self::LANG_IT_IT, self::LANG_IT_CH, self::LANG_JA, self::LANG_JA_JP, self::LANG_JA_JP_JP, self::LANG_KO, self::LANG_KO_KR, self::LANG_LV, self::LANG_LV_LV, self::LANG_LT, self::LANG_LT_LT, self::LANG_MK, self::LANG_MK_MK, self::LANG_MS, self::LANG_MS_MY, self::LANG_MT, self::LANG_MT_MT, self::LANG_NO, self::LANG_NO_NO, self::LANG_NO_NO_NY, self::LANG_PL, self::LANG_PL_PL, self::LANG_PT, self::LANG_PT_BR, self::LANG_PT_PT, self::LANG_RO, self::LANG_RO_RO, self::LANG_RU, self::LANG_RU_RU, self::LANG_SR, self::LANG_SR_BA, self::LANG_SR_ME, self::LANG_SR_CS, self::LANG_SR_RS, self::LANG_SK, self::LANG_SK_SK, self::LANG_SL, self::LANG_SL_SI, self::LANG_ES, self::LANG_ES_AR, self::LANG_ES_BO, self::LANG_ES_CL, self::LANG_ES_CO, self::LANG_ES_CR, self::LANG_ES_DO, self::LANG_ES_EC, self::LANG_ES_SV, self::LANG_ES_GT, self::LANG_ES_HN, self::LANG_ES_MX, self::LANG_ES_NI, self::LANG_ES_PA, self::LANG_ES_PY, self::LANG_ES_PE, self::LANG_ES_PR, self::LANG_ES_ES, self::LANG_ES_US, self::LANG_ES_UY, self::LANG_ES_VE, self::LANG_SV, self::LANG_SV_SE, self::LANG_TH, self::LANG_TH_TH, self::LANG_TH_TH_TH, self::LANG_TR, self::LANG_TR_TR, self::LANG_UK, self::LANG_UK_UA, self::LANG_VI, self::LANG_VI_VN, self::LANG_DE_BE, self::LANG_DE_LI, self::LANG_EN_BE, self::LANG_EN_BW, self::LANG_EN_BZ, self::LANG_EN_HK, self::LANG_EN_JM, self::LANG_EN_MH, self::LANG_EN_NA, self::LANG_EN_PK, self::LANG_EN_TT, self::LANG_EN_US_POSIX, self::LANG_EN_VI, self::LANG_EN_ZW, self::LANG_FR_MC, self::LANG_NB, self::LANG_NB_NO, self::LANG_ZH_HANS, self::LANG_ZH_HANS_CN, self::LANG_ZH_HANT, self::LANG_ZH_HANT_TW,);
    public static $LANGUAGES = array(self::LANGUAGE_CHINESE, self::LANGUAGE_CHINESE_TW, self::LANGUAGE_EN,);
    public static $LANGS_FAMILIAR = array(self::FAMILIAR_BEGINNER, self::FAMILIAR_INTERMEDIATE, self::FAMILIAR_FREQUENT,);
    public static $LOCALS = array('en_GB' => 'English(UK)', 'en_US' => 'English(US)', 'de' => 'Deutsch', 'nl' => 'Nederlands', 'fr' => 'Français', 'es' => 'Español', 'ca' => 'Català', 'it' => 'Italiano', 'pt_PT' => 'Português(PT)', 'pt_BR' => 'Português(BR)', 'no' => 'Norsk', 'fi' => 'Suomi', 'sv' => 'Svenska', 'da' => 'Dansk', 'cs' => 'Čeština', 'hu' => 'Magyar', 'ro' => 'Română', 'ja' => '日本語', 'zh_CN' => '简体中文', 'zh_TW' => '繁體中文', 'pl' => 'Polski', 'el' => 'Ελληνικά', 'ru' => 'Русский', 'tr' => 'Türkçe', 'bg' => 'Български', 'ar' => 'عربي', 'ko' => '한국어', 'he' => 'עברית', 'lv' => 'Latviski', 'uk' => 'Українська', 'id' => 'BahasaIndonesia', 'ms' => 'BahasaMalaysia', 'th' => 'ภาษาไทย', 'et' => 'Eesti', 'hr' => 'Hrvatski', 'lt' => 'Lietuvių', 'sk' => 'Slovenčina', 'sr' => 'Srpski', 'sl' => 'Slovenščina', 'vi' => 'TiếngViệt', 'tl' => 'Filipino', 'is' => 'Íslenska',);
    public static $MONEYS = array(121 => array('name' => 'CNY', 'symbol' => 'CNY',), 122 => array('name' => 'SGD', 'symbol' => 'SGD',), 123 => array('name' => 'HKD', 'symbol' => 'HKD',), 124 => array('name' => 'USD', 'symbol' => 'US$',), 125 => array('name' => 'GBP', 'symbol' => '£',), 126 => array('name' => 'DKK', 'symbol' => 'DKK',), 127 => array('name' => 'UAH', 'symbol' => 'UAH',), 128 => array('name' => 'ILS', 'symbol' => 'ILS',), 129 => array('name' => 'RUB', 'symbol' => 'RUB',), 130 => array('name' => 'BGN', 'symbol' => 'BGN',), 131 => array('name' => 'CAD', 'symbol' => 'CAD',), 132 => array('name' => 'HUF', 'symbol' => 'HUF',), 133 => array('name' => 'ZAR', 'symbol' => 'ZAR',), 134 => array('name' => 'QAR', 'symbol' => 'QAR',), 135 => array('name' => 'IDR', 'symbol' => 'IDR',), 136 => array('name' => 'INR', 'symbol' => 'INR',), 137 => array('name' => 'KZT', 'symbol' => 'KZT',), 138 => array('name' => 'COP', 'symbol' => 'COP',), 139 => array('name' => 'EGP', 'symbol' => 'EGP',), 140 => array('name' => 'MXN', 'symbol' => 'MXN',), 141 => array('name' => 'VEF', 'symbol' => 'VEF',), 142 => array('name' => 'BHD', 'symbol' => 'BHD',), 143 => array('name' => 'BRL', 'symbol' => 'R$',), 144 => array('name' => 'LVL', 'symbol' => 'LVL',), 145 => array('name' => 'NOK', 'symbol' => 'NOK',), 146 => array('name' => 'CZK', 'symbol' => 'CZK',), 147 => array('name' => 'MDL', 'symbol' => 'MDL',), 148 => array('name' => 'FJD', 'symbol' => 'FJD',), 149 => array('name' => 'TWD', 'symbol' => 'TWD',), 150 => array('name' => 'TRY', 'symbol' => 'TL',), 151 => array('name' => 'NZD', 'symbol' => 'NZD',), 152 => array('name' => 'JPY', 'symbol' => '¥',), 153 => array('name' => 'CLP', 'symbol' => 'CL$',), 154 => array('name' => 'GEL', 'symbol' => 'GEL',), 155 => array('name' => 'EUR', 'symbol' => '€',), 156 => array('name' => 'SAR', 'symbol' => 'SAR',), 157 => array('name' => 'PLN', 'symbol' => 'PLN',), 158 => array('name' => 'THB', 'symbol' => 'THB',), 159 => array('name' => 'AUD', 'symbol' => 'AUD',), 160 => array('name' => 'SEK', 'symbol' => 'SEK',), 161 => array('name' => 'CHF', 'symbol' => 'CHF',), 162 => array('name' => 'KWD', 'symbol' => 'KWD',), 163 => array('name' => 'LTL', 'symbol' => 'LTL',), 164 => array('name' => 'JOD', 'symbol' => 'JOD',), 165 => array('name' => 'NAD', 'symbol' => 'NAD',), 166 => array('name' => 'RON', 'symbol' => 'RON',), 167 => array('name' => 'XOF', 'symbol' => 'XOF',), 168 => array('name' => 'AZN', 'symbol' => 'AZN',), 169 => array('name' => 'OMR', 'symbol' => 'OMR',), 170 => array('name' => 'ARS', 'symbol' => 'AR$',), 171 => array('name' => 'AED', 'symbol' => 'AED',), 172 => array('name' => 'KRW', 'symbol' => 'KRW',), 173 => array('name' => 'MYR', 'symbol' => 'MYR',),);
    public static $CONTACTS = array(self::CONTACT_TEL, self::CONTACT_QQ, self::CONTACT_WEIXIN, self::CONTACT_EMAIL,);
    public static $LEPEI_TYPES = array(self::LEPEI_PROFESSIONAL, self::LEPEI_STUDENT, self::LEPEI_HOST, self::LEPEI_OVERSEAS,);
    public static $TRAVEL_SERVICES = array(self::SERVICE_CAR, self::SERVICE_HOTEL, self::SERVICE_INTRODUCTION, self::SERVICE_SHOPPING_GUIDE, self::SERVICE_FOOD_GUIDE, self::SERVICE_IN_OUT, self::SERVICE_TRANSLATION, self::SERVICE_SCENE_INTRODUCTION, self::SERVICE_ORDER_CAR, self::SERVICE_ORDER_HOTEL,);
    public static $TRAVEL_THEMES = array(self::THEME_GRADUATION, self::THEME_HONEY_MOON, self::THEME_ON_FOOT, self::THEME_SHOPPING, self::THEME_FOOD, self::THEME_EXPLORE, self::THEME_GROUP,);

}
