<?php
/**
 * User: wangfeng
 * Date: 13-6-14
 * Time: 下午9:26
 */
class TravelService extends  BaseModel
{
    public function getSchema()
    {
        return array(
            'tid'=>new Schema('translate_id',Constants::SCHEMA_INT),
        );
    }
}