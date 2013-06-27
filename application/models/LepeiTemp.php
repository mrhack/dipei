<?php
/**
 * 申请地陪时的临时存储表
 * User: wangfeng
 * Date: 13-6-12
 * Time: 下午7:09
 * @method static LepeiTempModel getInstance()
 */
class LepeiTempModel extends  UserModel
{
    use Strategy_Singleton;

    public function getCollectionName()
    {
        return 'lepeitemp';
    }
}
