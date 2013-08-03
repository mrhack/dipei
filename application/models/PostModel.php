<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:24
 * @method static PostModel getInstance()
 */
class PostModel extends BaseModel
{
    use Strategy_Singleton;
    public function getSchema()
    {
        return array(
            '_id'=>new Schema('_id',Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            'tp'=>new Schema('type',Constants::SCHEMA_INT),
            't' => new Schema('title', Constants::SCHEMA_STRING, array(
                AppValidators::newLength(array('$le' => 100), _e('标题不得超过100字')),
            )),
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
            'lk' => new Schema('like', Constants::SCHEMA_INT),
            'c_t'=> new Schema('create_time',Constants::SCHEMA_DATE),
        );
    }
}