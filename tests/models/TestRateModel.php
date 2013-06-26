<?php
/**
 * User: wangfeng
 * Date: 13-6-26
 * Time: 上午1:12
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class TestRateModel extends DipeiTestCase
{

    public function testConvertRate()
    {
        $converted=RateModel::getInstance()->convertRate(10000,'USD','CNY');
        $this->assertEquals(1626, intval($converted));
    }
}