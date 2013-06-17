<?php
/**
 * User: wangfeng
 * Date: 13-6-13
 * Time: 上午12:14
 *
 * @method static LocationModel getInstance()
 */
class LocationModel extends  BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            'n'=>new Schema('name',Constants::SCHEMA_STRING),
            'nid'=>new Schema('name_tid',Constants::SCHEMA_INT),//tid
            'cs'=>array(
                new Schema('counts',Constants::SCHEMA_OBJECT),
                'p'=>new Schema('project',Constants::SCHEMA_INT),
                'd'=>new Schema('dipei',Constants::SCHEMA_INT)
            ),
            'dsc'=>new Schema('desc'),
            'pt'=>new Schema('path',Constants::SCHEMA_ARRAY),
            'ims'=>new Schema('images',Constants::SCHEMA_ARRAY)
            //....
        );
    }
}
