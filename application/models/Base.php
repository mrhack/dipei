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
        return array_shift($this->fetch($condition, $fields));

    }

    public function &fetch($condition = array(),$fields=array())
    {
        try{
            $cursor = $this->getCollection()->find($condition,$fields);
        }catch (Exception $ex){
            $this->getLogger()->error('fetch error:'.$ex->getMessage(),array('condition'=>$condition,'fields'=>$fields));
            throw new AppException(Constants::CODE_MONGO);
        }
        $datas = array();
        foreach ($cursor as $data) {
            $datas[] = $data;
        }
        return $datas;
    }

    public abstract function getSchema();

    public function &__formatSchema(&$data,$formatSchema,$reverse=false){
        $formated=array();
        foreach($formatSchema as $fromK=>$toK){
            if(is_array($toK)){
                if($reverse && isset($data[$toK[0]->name])){
                    $formated[$fromK] = $this->__formatSchema($data[$toK[0]->name], $toK, $reverse);
                }else if(isset($data[$fromK])){
                    $formated[$toK[0]->name]=$this->__formatSchema($data[$fromK],$toK,$reverse);
                }
            }else {
                if($reverse && isset($data[$toK->name])){
                    $formated[$fromK] = $data[$toK->name];
                }else if(isset($data[$fromK])){
                    $formated[$toK->name] = $data[$fromK];
                }
            }
        }
        return $formated;
    }

    public function &__getSchema(){
        static $schema=null;
        if(is_null($schema)){
            $schema=$this->getSchema();
        }
        return $schema;
    }


    public function &format(&$data,$reverse=false)
    {
        if(is_array($data)){
            $formated = $this->__formatSchema($data,$this->__getSchema(),$reverse);
            return $formated;
        }else{
            return $data;
        }
    }

    public function insert($data, $batch = false)
    {
        if (!$batch) {
            if(!isset($data['_id'])){
                $data['_id']=$this->getNextId();
            }
            $this->validate($data);
            try{
                return $this->getCollection()->insert($data);
            }catch (Exception $ex){
                $this->getLogger()->error('insert error:' . $ex->getMessage(), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO);
            }
        } else {
            foreach ($data as $v) {
                $v['_id']=$this->getNextId();
                $this->validate($v);
            }
            try{
                return $this->getCollection()->batchInsert($data);
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
            } else {
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
                return $this->update($data, null, array('upsert'=>true));
            }catch (Exception $ex){
                $this->getLogger()->error('save error:' . $ex->getMessage(), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO);
            }
        }else{
            foreach($data as $v){
                $this->save($v, false);
            }
        }
    }

    /**
     * @param $data
     * @throws AppException
     */
    public function validate($data)
    {
        if(empty($data)){
            $this->getLogger()->addError('invalid model', $data);
            throw new AppException(Constants::CODE_INVALID_MODEL);
        }
    }
}