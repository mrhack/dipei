<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午7:40
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class LocationModelTest extends DipeiTestCase
{

    public function testSearchLocation()
    {
//        Constants::$DB_LEPEI='lepei';
        var_dump(LocationModel::getInstance()->searchLocation('zhong', 10));
        var_dump(LocationModel::getInstance()->searchLocation('中', 10,2,3));

        var_dump(LocationModel::getInstance()->searchLocation('北', 50,2,2));
        AppLocal::init('en');
        var_dump(LocationModel::getInstance()->searchLocation('am', 10));
    }


}
