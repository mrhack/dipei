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

    public function __construct()
    {
        $this->ensureIndex(array('ptc' => 1));
        $this->ensureIndex(array('pt' => 1));
        $this->ensureIndex(array('pt' => 1,'c.d'=>-1));
        $this->ensureIndex(array('pt' => 1,'c.p'=>-1));
        $this->ensureIndex(array('sid'=>1),array('unique'=>true,'dropDups'=>true,'sparse'=>true));
    }

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
            'ims'=>new Schema('images',Constants::SCHEMA_ARRAY),
            'lk'=>new Schema('like',Constants::SCHEMA_INT),
            //....
        )+array(
            'ptc'=>new Schema('path_count',Constants::SCHEMA_INT)
        );
    }

    private function ensureIndexFields(&$info)
    {
        if(isset($info['pt'])){
            $info['ptc'] = count($info['pt']);
        }
    }

    public function updateLocation($locationInfo)
    {
        $this->ensureIndexFields($locationInfo);
        $this->update($locationInfo);
    }

    public function createLocation($locationInfo)
    {
        $this->ensureIndexFields($locationInfo);
        $this->insert($locationInfo);
    }


    public function isCountry($location)
    {
        return !empty($location) && isset($location['pt']) && count($location['pt']) == 1;
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
        $roots = $this->fetch(
            MongoQueryBuilder::newQuery()->query(array('ptc' => 0))->comment(__METHOD__)->build()
        );
        return $roots;
    }

    public function &getCountries()
    {
        $countries = $this->fetch(
            MongoQueryBuilder::newQuery()->query(array('ptc' => 1))->comment(__METHOD__)->build()
        );
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
    public function searchLocation($k,$limit=15,$minPath=2,$maxPath=999999,$local=null){
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
            if(count($location['pt'])<$minPath || count($location['pt'])>$maxPath){
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
