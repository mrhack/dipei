<?php
/**
 * User: wangfeng
 * Date: 13-8-31
 * Time: 下午10:23
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class CacheModelTest extends DipeiTestCase
{
    public function testSet()
    {
        $key='key1';
        $val='val1';
        CacheModel::getInstance()->set($key, $val);
        return $key;
    }

    /**
     * @depends testSet
     */
    public function testGet($key)
    {
        $val=CacheModel::getInstance()->get($key);
        $this->assertEquals('val1', $val);
    }
}
