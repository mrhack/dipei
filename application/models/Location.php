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
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            'n'=>new Schema('name',Constants::SCHEMA_STRING),
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

    /**
     * return with _id and name
     * @param $k
     * @param int $limit
     * @param null $local
     * @return array
     */
    public function searchLocation($k,$limit=10,$local=null){
        $k = strval($k);
        if(empty($local)){
            $local=AppLocal::currentLocal();
        }
        $queryBuilder=new MongoQueryBuilder();
        $queryBuilder->limit($limit);
        if(Helper_Local::getInstance()->isChinaLocal($local)){
            $queryBuilder->query(array(
                '$or'=>array(
                    array('_id'=>array('$gt'=>999,'$lt'=>1000000),Constants::LANG_PY=>new MongoRegex("/$k/")),
                    array('_id'=>array('$gt'=>999,'$lt'=>1000000),$local=>new MongoRegex("/$k/"))
                )
            ));
        }else{
            $queryBuilder->query(
                    array('_id'=>array('$gt'=>999,'$lt'=>1000000),$local=>new MongoRegex("/$k/")));
        }
        $query=$queryBuilder->build();
        $translateModel=TranslationModel::getInstance();
        $translates=$translateModel->fetch($query);
        $results=array();
        foreach($translates as $translate){
            $results[]=array(
                '_id'=>$translate['_id'],
                'n'=>$translateModel->translateWord($translate,$local)
            );
        }
        return $results;
    }
}
