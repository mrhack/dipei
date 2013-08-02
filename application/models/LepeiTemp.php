<?php
/**
 * 申请地陪时的临时存储表
 * User: wangfeng
 * Date: 13-6-12
 * Time: 下午7:09
 * @method static LepeiTempModel getInstance()
 * @deprecated 不对人审核，仅对项目审核
 */
class LepeiTempModel extends  UserModel
{
    public function getCollectionName()
    {
        return 'lepeitemp';
    }
}
