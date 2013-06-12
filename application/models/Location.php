<?php
/**
 * User: wangfeng
 * Date: 13-6-13
 * Time: 上午12:14
 */
class LocationModel extends  BaseModel
{
    public function getSchema()
    {
        return array(
            'n'=>new Schema('name',Constants::SCHEMA_STRING),
            'cs'=>array(
                new Schema('counts',Constants::SCHEMA_OBJECT),
                'p'=>new Schema('project',Constants::SCHEMA_INT),
                'd'=>new Schema('dipei',Constants::SCHEMA_INT)
            ),
            'dsc'=>new Schema('desc'),
            'py'=>new Schema('pinyin')
            //....
        );
    }
}
