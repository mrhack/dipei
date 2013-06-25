<?php

class Schema
{
    public $name;
    public $format;
    public $validators;

    public function __construct($name,$format=null,$validators=null){
        $this->name=$name;
        $this->format=$format;
        $this->validators=$validators;
    }
}
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:25
 */
abstract class BaseModel
{
    use AppComponent;

    public function getNextId()
    {
        $mongo = AppMongo::getInstance(Constants::CONN_MONGO_STRING);
        $idAllocator=$mongo->selectCollection(Constants::DB_LEPEI, 'id_allocator');
        $idAllocator->update(array(),array('$inc' => array($this->getCollectionName()=>1)), array('upsert'=>true));
        $ids=$idAllocator->findOne(array(),array($this->getCollectionName()=>true));
        return $ids[$this->getCollectionName()];
    }

    public function getLastId()
    {
        $mongo = AppMongo::getInstance(Constants::CONN_MONGO_STRING);
        $idAllocator=$mongo->selectCollection(Constants::DB_LEPEI, 'id_allocator');
        $ids=$idAllocator->findOne(array(),array($this->getCollectionName()=>true));
        if(empty($ids)){
            return 0;
        }else{
            return $ids[$this->getCollectionName()];
        }
    }

    public function getCollectionName()
    {
        static $collectionName=null;
        if($collectionName !=null) return $collectionName;
        $realClassName = get_class($this);
        $realClassName = preg_replace('/(.+)Model/i', '$1', $realClassName);
        $collectionName=strtolower($realClassName);
        return $collectionName;
    }

    /**
     * @return MongoCollection
     */
    public function getCollection()
    {
        $mongo = AppMongo::getInstance(Constants::CONN_MONGO_STRING);
        $collection = $mongo->selectCollection(Constants::DB_LEPEI, $this->getCollectionName());
        return $collection;
    }

    public function count($condition = array())
    {
        try{
            return $this->getCollection()->count($condition);
        }catch (Exception $ex){
            $this->getLogger()->error('count error:' . $ex->getMessage(), $condition);
            throw new AppException(Constants::CODE_MONGO);
        }
    }

    public function fetchOne($condition=array(),$fields=array()){
        return array_shift($this->fetch($condition, $fields,Constants::INDEX_MODE_ARRAY));

    }

    public function &fetch($condition = array(),$fields=array(),$indexMode=Constants::INDEX_MODE_ID)
    {
        try{
            //extend mongo find modification
            if(isset($condition['$limit'])){
                $limit = $condition['$limit'];
                unset($condition['$limit']);
            }
            if(isset($condition['$skip'])){
                $skip = $condition['$skip'];
                unset($condition['$skip']);
            }
            $cursor = $this->getCollection()->find($condition,$fields);
            if(!empty($limit)){
                $cursor->limit($limit);
            }
            if(!empty($skip)){
                $cursor->skip($skip);
            }
        }catch (Exception $ex){
            $this->getLogger()->error('fetch error:'.$ex->getMessage(),array('condition'=>$condition,'fields'=>$fields));
            throw new AppException(Constants::CODE_MONGO);
        }
        $datas = array();
        foreach ($cursor as $data) {
            if($indexMode==Constants::INDEX_MODE_ARRAY){
                $datas[]=$data;
            }else{
                $datas[$data['_id']] = $data;
            }
        }
        return $datas;
    }

    public abstract function getSchema();

    public function &__formatSchema(&$data,$formatSchema,$reverse=false){
        $formated=array();
        foreach($formatSchema as $fromK=>$toK){
            if(is_array($toK)){
                if(isset($toK['$key']) || isset($toK['$value'])){
                    if(isset($formated[$fromK]) && $reverse){
                    }else if(isset($formated[$toK[0]->name])){
                    }
                }
                if($reverse && isset($data[$toK[0]->name]) && is_array($data[$toK[0]->name])){
                    if($toK[0]->format == Constants::SCHEMA_ARRAY){//array mode
                        foreach($data[$toK[0]->name] as $k=>$v){
                            $formated[$fromK][$k] = $this->__formatSchema($v, $toK, $reverse);
                        }
                    }else{//object mode
                        $formated[$fromK] = $this->__formatSchema($data[$toK[0]->name], $toK, $reverse);
                    }
                    if(isset($toK['$key']) || isset($toK['$value'])){
                        foreach($data[$toK[0]->name] as $k=>$v){
                            if(empty($toK[$k])){
                                $k = $this->__castData($k, $toK['$key']->format);
                            }
                            if(empty($toK[$k])){
                                $v = $this->__castData($v, $toK['$value']->format);
                            }
                            $formated[$fromK][$k]=$v;
                        }
                    }
                }else if(isset($data[$fromK]) && is_array($data[$fromK])){
                    if($toK[0]->format == Constants::SCHEMA_ARRAY){//array mode
                        foreach($data[$fromK] as $k=>$v){
                            $formated[$toK[0]->name][$k] = $this->__formatSchema($v,$toK,$reverse);
                        }
                    }else{//object mode
                        $formated[$toK[0]->name]=$this->__formatSchema($data[$fromK],$toK,$reverse);
                    }
                    if(isset($toK['$key']) || isset($toK['$value'])){
                        foreach($data[$fromK] as $k=>$v){
                            if(empty($toK[$k])){
                                $k = $this->__castData($k, $toK['$key']->format);
                            }
                            if(empty($toK[$k])){
                                $v = $this->__castData($v, $toK['$value']->format);
                            }
                            $formated[$toK[0]->name][$k]=$v;
                        }
                    }
                }
            }else {
                if($reverse && isset($data[$toK->name])){
                    $formated[$fromK] = $this->__castData($data[$toK->name],$toK->format);
                }else if(isset($data[$fromK])){
                    $formated[$toK->name] = $this->__castData($data[$fromK],$toK->format);
                }
            }
        }
        return $formated;
    }

    public function __castData($val,$format)
    {
        if(empty($format)){
            return $val;
        }
        switch($format){
            case Constants::SCHEMA_INT:
                return intval($val);
            case Constants::SCHEMA_STRING:
                return strval($val);
            default:
                return $val;
        }
    }

    public function &__getSchema(){
        static $schema=null;
        if(is_null($schema)){
            $schema=$this->getSchema();
        }
        return $schema;
    }


    public function &format($data,$reverse=false)
    {
        if(is_array($data)){
            $formated = $this->__formatSchema($data,$this->__getSchema(),$reverse);
            return $formated;
        }else{
            return $data;
        }
    }

    public function formats($datas,$reverse=false){
        $formated=array();
        foreach($datas as $k=>&$data){
            $formated[$k] = $this->format($data, $reverse);
        }
        return $formated;
    }

    public function insert($data, $batch = false)
    {
        if (!$batch) {
            $inserted=null;
            if(!isset($data['_id'])){
                $data['_id']=$this->getNextId();
            }
            $inserted = $data['_id'];
            $this->validate($data);
            try{
                return array_merge(array('inserted'=>$inserted),$this->getCollection()->insert($data));
            }catch (Exception $ex){
                $this->getLogger()->error('insert error:' . $ex->getMessage(), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO);
            }
        } else {
            $inserted=array();
            foreach ($data as $v) {
                $v['_id']=$this->getNextId();
                $inserted[] = $v['_id'];
                $this->validate($v);
            }
            try{
                return array_merge(array('inserted' => $inserted), $this->getCollection()->batchInsert($data));
            }catch (Exception $ex){
                $this->getLogger()->error('insert error:' . $ex->getMessage(), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO);
            }
        }
    }

    public function remove($data)
    {
        if(empty($data)){
            throw new AppException(Constants::CODE_REMOVE_NEED_WHERE);
        }
        try{
            return $this->getCollection()->remove($data);
        }catch (Exception $ex){
            $this->getLogger()->error('remove error:' . $ex->getMessage(), $data);
            throw new AppException(Constants::CODE_MONGO);
        }
    }

    public function update($data, $find = null,$options=array())
    {
        $this->validate($data);
        $findById = empty($find);
        if ($findById) {
            if (isset($data['_id'])) {
                $find = array('_id' => $data['_id']);
            } else if(isset($data['upsert']) && $data['upsert']){
                $find=array();
            }else{
                throw new AppException(Constants::CODE_UPDATE_NEED_WHERE);
            }
        }
//        if(empty($options) && !$findById){
//            $options=array('multiple'=>true);
//        }
        unset($data['_id']);
        try{
            return $this->getCollection()->update($find, array('$set' => $data), $options);
        }catch (Exception $ex){
            $this->getLogger()->error('update error:' . $ex->getMessage(), array('data'=>$data,'options'=>$options));
            throw new AppException(Constants::CODE_MONGO);
        }
    }

    public function save($data, $batch = false)
    {
        if (!$batch) {
            $this->validate($data);
            try{
                return $this->update($data,null,array('upsert'=>true));
            }catch (Exception $ex){
                $this->getLogger()->error('save error:' . $ex->getMessage(), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO);
            }
        }else{
            $returns=array();
            foreach($data as $v){
                array_push($returns,$this->save($v, false));
            }
            return $returns;
        }
    }

    /**
     * @param $data
     * @throws AppException
     */
    public function validate($data)
    {
        if(empty($data)){
            $this->getLogger()->error('invalid model', $data);
            throw new AppException(Constants::CODE_INVALID_MODEL);
        }
    }
}