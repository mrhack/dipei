<?php
/**
 * User: wangfeng
 * Date: 13-6-15
 * Time: 上午12:42
 */
class MoneyModel extends BaseModel{

    public function getSchema()
    {
        return array(
            'sm'=> new Schema('symbol',Constants::SCHEMA_STRING),
            'tid'=> new Schema('translate_id',Constants::SCHEMA_INT)
        );
    }
}