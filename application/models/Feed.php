<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:15
 *@method static FeedModel getInstance()
 */
class FeedModel extends  BaseModel
{
    use Strategy_Singleton;
    public function getSchema()
    {
        return array(
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            //type post:post_id project:project_id message_group:user_id(target)
            'oid'=>new Schema('oid',Constants::SCHEMA_INT),
            //author uid
            'uid'=>new Schema('uid',Constants::SCHEMA_INT),
            //type maybe post,qa,project,message
            'tp'=>new Schema('type',Constants::SCHEMA_INT),
            's'=>new Schema('status',Constants::SCHEMA_INT,AppValidators::newStatusValidators()),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE),
            'r_t'=>new Schema('reply_time',Constants::SCHEMA_DATE),
            //last reply id
            'l_r'=>new Schema('last_reply_id',Constants::SCHEMA_INT)
        );
    }
}