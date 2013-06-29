<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:21
 */
require_once __DIR__ . '/../DipeiTestCase.php';

class TestUserModel extends DipeiTestCase
{
    public function testGrantUser()
    {
        UserModel::getInstance()->grantLepei(5);
        var_dump(UserModel::getInstance()->fetchOne(array('_id'=>5)));
    }
}
