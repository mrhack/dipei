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
     * merge a virtual global location
     */
    public function getGlobalLocation()
    {
        $roots=$this->getRootLocations();
        $location=array(
            '_id'=>0,
            'n'=>'global',
            'pt'=>array(),
            'dsc'=>'virtual global location',
            'ims'=>array()
        );
        foreach($roots as $root){
            $location['c']['d'] += $root['c']['d'];
            $location['c']['p'] += $root['c']['p'];
            foreach($root['tm_c'] as $k=>$v){
                $location['tm_c'][$k]+=$v;
            }
        }
        return $location;
    }

    public function &getRootLocations()
    {
        $roots = $this->fetch(array('pt' => array('$size' => 0)));
        return $roots;
    }

    public function &getCounties()
    {
        $countries=$this->fetch(array('pt'=>array('$size'=>1)));
        return $countries;
    }

    public function searchCountry($k,$local=null){
        $k = strval($k);
        if(empty($local)){
            $local=AppLocal::currentLocal();
        }
        $queryBuilder=new MongoQueryBuilder();
        $queryBuilder->sort(array('_id'=>1))->limit(20);
        if(Helper_Local::getInstance()->isChinaLocal($local)){
            $queryBuilder->query(array(
                '$or'=>array(
                    array('_id'=>array('$gt'=>999,'$lt'=>1000000),Constants::LANG_PY=>new MongoRegex("/$k/")),
                    array('_id'=>array('$gt'=>999,'$lt'=>1000000),$local=>new MongoRegex("/$k/i"))
                )
            ));
        }else{
            $queryBuilder->query(
                array('_id'=>array('$gt'=>999,'$lt'=>1000000),$local=>new MongoRegex("/$k/i")));
        }
        $query=$queryBuilder->build();
        $translateModel=TranslationModel::getInstance();
        $translates=$translateModel->fetch($query,array(),Constants::INDEX_MODE_ID);
        $lids=array();
        foreach($translates as $translate){
            $lids[] = $translate['_id']-1000;
        }
        $locations=$this->fetch(array('_id'=>array('$in'=>$lids)));
        $results=array();
        foreach($locations as $location){
            if(count($location['pt'])!=1){
                continue;
            }
            $locationTid=$location['_id']+1000;
            $results[]=array(
                'id'=>$location['_id'],
                'name'=>$translateModel->translateWord($translates[$locationTid],$local),
            );
        }
        return $results;
    }

    /**
     * return with _id and name
     * @param $k
     * @param int $limit
     * @param null $local
     * @return array
     */
    public function searchLocation($k,$limit=15,$local=null){
        $k = strval($k);
        if(empty($local)){
            $local=AppLocal::currentLocal();
        }
        $queryBuilder=new MongoQueryBuilder();
        $queryBuilder->sort(array('_id'=>-1))->limit($limit);
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
        $translates=$translateModel->fetch($query,array(),Constants::INDEX_MODE_ID);
        $results=array();
//        foreach($translates as $translate){
//            $results[]=array(
//                'id'=>$translate['_id'],
//                'name'=>$translateModel->translateWord($translate,$local)
//            );
//        }
//        return $results;
        $lids=array();
        foreach($translates as $translate){
            $lids[] = $translate['_id']-1000;
        }
        //fetch parent tids
        $locations=$this->fetch(array('_id'=>array('$in'=>$lids)));
        $parentTids=array();
        foreach($locations as $k=>$location){
            if(count($location['pt'])<2){
                unset($locations[$k]);
                continue;
            }
            $parentTids[$k] = array_pop($location['pt'])+1000;
        }
        //merge translates
        $parentTranslates=$translateModel->fetch(array('_id'=>array('$in'=>$parentTids)));
        foreach($parentTranslates as $k=>$translate){
            $translates[$k]=$translate;
        }
        foreach($locations as $location){
            $locationTid=$location['_id']+1000;
            $locationParentTid = $parentTids[$location['_id']];
            $results[]=array(
                'id'=>$location['_id'],
                'name'=>$translateModel->translateWord($translates[$locationTid],$local),
                'parentName'=>empty($locationParentTid)?'':$translateModel->translateWord($translates[$locationParentTid],$local),
            );
        }
        return $results;
    }
}
