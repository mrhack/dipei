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
            'c'=>array(
                new Schema('counts',Constants::SCHEMA_OBJECT),
                'p'=>new Schema('project',Constants::SCHEMA_INT),
                'd'=>new Schema('dipei',Constants::SCHEMA_INT)
            ),
            'tm_c'=>array(
                new Schema('travel_theme_counts',Constants::SCHEMA_OBJECT),
                '$key'=> new Schema('',Constants::SCHEMA_INT),//tid
                '$value'=>new Schema('',Constants::SCHEMA_INT),//count
            ),
            'dsc'=>new Schema('desc'),
            'pt'=>new Schema('path',Constants::SCHEMA_ARRAY),
            'ims'=>new Schema('images',Constants::SCHEMA_ARRAY)
            //....
        );
    }
}
