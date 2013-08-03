<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:30
 * @method static ReplyModel getInstance()
 */
class ReplyModel extends BaseModel
{
    use Strategy_Singleton;
    public function getSchema()
    {
        return array(
            '_id'=>new Schema('_id',Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            'pid'=>new Schema('pid',Constants::SCHEMA_INT),
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
        );
    }
}