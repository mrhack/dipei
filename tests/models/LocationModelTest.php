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
        var_dump(LocationModel::getInstance()->searchLocation('zhong', 10, 'zh_CN'));
        var_dump(LocationModel::getInstance()->searchLocation('中', 10, 'zh_CN'));
        var_dump(LocationModel::getInstance()->searchLocation('am', 10, 'en'));
    }


}
