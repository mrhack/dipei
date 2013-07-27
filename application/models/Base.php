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

    private $cache=array();

    public function ensureIndex($index, $options=array())
    {
        $options['background']=true;
        //TODO cache if indexed
        $this->getCollection()->ensureIndex($index, $options);
    }

    public function getAllocatorCollectionName()
    {
        return 'id_allocator';
    }

    public function getNextId($collectionName=null)
    {
        if(is_null($collectionName)){
            $collectionName=$this->getCollectionName();
        }
        $mongo = AppMongo::getInstance(Constants::$CONN_MONGO_STRING);
        $idAllocator=$mongo->selectCollection(Constants::$DB_LEPEI, $this->getAllocatorCollectionName());
        $idAllocator->update(array(),array('$inc' => array($collectionName=>1)), array('upsert'=>true));
        $ids=$idAllocator->findOne(array(),array($collectionName=>true));
        return $ids[$collectionName];
    }

    public function getLastId($collectionName=null)
    {
        if(is_null($collectionName)){
            $collectionName=$this->getCollectionName();
        }
        $mongo = AppMongo::getInstance(Constants::$CONN_MONGO_STRING);
        $idAllocator=$mongo->selectCollection(Constants::$DB_LEPEI, $this->getAllocatorCollectionName());
        $ids=$idAllocator->findOne(array(),array($collectionName=>true));
        if(empty($ids)){
            return 0;
        }else{
            return $ids[$collectionName];
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
        $mongo = AppMongo::getInstance(Constants::$CONN_MONGO_STRING);
        $collection = $mongo->selectCollection(Constants::$DB_LEPEI, $this->getCollectionName());
        return $collection;
    }

    public function count($condition = array())
    {
        try{
            $this->getLogger()->debug('count', func_get_args());
            return $this->getCollection()->count($condition);
        }catch (Exception $ex){
            $this->getLogger()->error('count error:' . $ex->getMessage(), $condition);
            throw new AppException(Constants::CODE_MONGO);
        }
    }

    public function isValidId($id)
    {
        $data = $this->fetchOne(array('_id' => intval($id)));
        return !empty($data);
    }

    public function fetchOne($condition=array(),$fields=array()){
        $rets = $this->fetch($condition, $fields, Constants::INDEX_MODE_ARRAY);
        return array_shift($rets);

    }

    private function _buildCacheKey($cond,$fields)
    {
        if(!$cond){
            $cond=array();
        }
        if(!$fields){
            $fields = array();
        }
        return json_encode($cond) .'-'. json_encode($fields);
    }

    public function clearCache()
    {
        unset($this->cache);
        $this->cache=array();
    }

    public function fetch($condition = array(),$fields=array(),$indexMode=Constants::INDEX_MODE_ID)
    {
        $this->getLogger()->debug('fetch', func_get_args());
        //XXX remove cache?
        $cacheKey = $this->_buildCacheKey($condition, $fields);
        if(isset($this->cache[$cacheKey])){
            $cache = $this->cache[$cacheKey];
            $this->getLogger()->debug('cached',func_get_args());
            if($indexMode == Constants::INDEX_MODE_ID){
                foreach($cache as $item){
                    $cache[$item['_id']]=$item;
                }
                return $cache;
            }else{
                return array_values($cache);
            }
        }

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
            $datas = array();
            foreach ($cursor as $data) {
                if($indexMode==Constants::INDEX_MODE_ARRAY){
                    $datas[]=$data;
                }else{
                    $datas[$data['_id']] = $data;
                }
            }
            $this->cache[$cacheKey]=$datas;
            return $datas;
        }catch (Exception $ex){
            $this->getLogger()->error('fetch error:'.$ex->getMessage(),array('condition'=>$condition,'fields'=>$fields));
            throw new AppException(Constants::CODE_MONGO);
        }
    }

    public abstract function getSchema();

    public function __validateSchema($data,$root,$schemaObj)
    {
        $validateResult=array();
        foreach($schemaObj as $field=>$schema){
            if(is_array($schema)){
                if(isset($data[$field]) && is_array($data[$field])){
                    if($schema[0]->format == Constants::SCHEMA_ARRAY){//array mode
                        foreach($data[$field] as $k=>$v){
                            $validateResult[$schema[0]->name][$k] = $this->__validateSchema($v, $root, $schema);
                        }
                    }else{
                        $validateResult[$schema[0]->name] = $this->__validateSchema($data[$field], $root, $schema);
                    }
                    if(isset($schema['$key']) || isset($schema['$value'])){
                        foreach($data[$field] as $k=>$v){
                            if(isset($schema['$key']) &&
                                (is_numeric($k) || empty($schema[$k]))){
                                $validateResult[$schema[0]->name][$k] = $this->__validateSchema($k, $root, $schema['$key']);
                            }
                            if(isset($schema['$value']) &&
                                (is_numeric($k) || empty($schema[$k]))){
                                $validateResult[$schema[0]->name][$k] = $this->__validateSchema($k, $root, $schema['$value']);
                            }
                        }
                    }
                }
            }else if(isset($data[$field]) && !empty($data[$field])){
                $validators=$schema->validators;
                if(is_array($validators)){
                    foreach($validators as $validator){
                        if(!$validator->validate($data[$field],$field,$root)){
                            $validateResult[$schema->name][]=$validator->errorMsg;
                        }
                    }
                }
            }
        }
        return $validateResult;
    }

    public function __formatSchema(&$data,$formatSchema,$reverse=false){
        //TODO refactor
        $formated=array();
        foreach($formatSchema as $fromK=>$toK){
            if(is_array($toK)){
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
                            if (isset($toK['$key']) &&
                                (is_numeric($k) || empty($toK[$k]))) {
                                $k = $this->__castData($k, $toK['$key']->format);
                            }
                            if(isset($toK['$value']) &&
                                (is_numeric($k) || empty($toK[$k]))){
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
                            if(isset($toK['$key']) &&
                                (is_numeric($k) || empty($toK[$k]))){
                                $k = $this->__castData($k, $toK['$key']->format);
                            }
                            if(isset($toK['$value']) &&
                                (is_numeric($k) || empty($toK[$k]))){
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
            case Constants::SCHEMA_DOUBLE:
                return doubleval($val);
            case Constants::SCHEMA_DATE:
                if(is_numeric($val)){
                    return new MongoDate($val);
                }
                return $val;
            default:
                return $val;
        }
    }

    public function __getSchema(){
        static $schema=null;
        if(is_null($schema)){
            $schema=$this->getSchema();
        }
        return $schema;
    }


    public function format($data,$reverse=false,$root=null)
    {
        $rootSchema=$this->__getSchema();
        if(!empty($root)){
            foreach(explode('.',$root) as $root){
                $rootSchema = $rootSchema[$root];
            }
        }
        if(is_array($data)){
            $formated = $this->__formatSchema($data,$rootSchema,$reverse);
            return $formated;
        }else{
            return $data;
        }
    }

    /**
     * @param $data
     * @throws AppException
     */
    public function validate($data,$root=null)
    {
        $rootSchema=$this->__getSchema();
        if(!empty($root)){
            foreach(explode('.',$root) as $root){
                $rootSchema = $rootSchema[$root];
            }
        }
        if(!empty($rootSchema)){
            $validateResult = $this->__validateSchema($data, $data, $rootSchema);
            array_walk_recursive($validateResult,function($v) use($data,$validateResult){
                if(!empty($v)){
                    $this->getLogger()->error('invalid model', $data);
                    throw new AppException(Constants::CODE_INVALID_MODEL,'',$validateResult);
                }
            });
        }
    }

    public function formats($datas,$reverse=false,$root=null){
        $formated=array();
        foreach($datas as $k=>$data){
            $formated[$k] = $this->format($data, $reverse,$root);
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
                $ret= array_merge(array('inserted'=>$inserted),$this->getCollection()->insert($data));
                $this->getLogger()->info(sprintf('insert %s success',$this->getCollectionName()), $data);
                $this->clearCache();
                return $ret;
            }catch (Exception $ex){
                $this->getLogger()->error(sprintf('insert %s error:%s',$this->getCollectionName(), $ex->getMessage()), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO,'',array(),$ex);
            }
        } else {
            $inserted=array();
            foreach ($data as $v) {
                $v['_id']=$this->getNextId();
                $inserted[] = $v['_id'];
                $this->validate($v);
            }
            try{
                $ret= array_merge(array('inserted' => $inserted), $this->getCollection()->batchInsert($data));
                $this->getLogger()->info(sprintf('insert multi %s success',$this->getCollectionName()),$data);
                $this->clearCache();
                return $ret;
            }catch (Exception $ex){
                $this->getLogger()->error(sprintf('insert multi %s error:%s',$this->getCollectionName(),$ex->getMessage()), array('data'=>$data,'batch'=>$batch));
                throw new AppException(Constants::CODE_MONGO,'',array(),$ex);
            }
        }
    }

    public function remove($data)
    {
        if(empty($data)){
            throw new AppException(Constants::CODE_REMOVE_NEED_WHERE);
        }
        try{
            $ret = $this->getCollection()->remove($data);
            $this->getLogger()->info(sprintf('remove %s success',$this->getCollectionName()), $data);
            $this->clearCache();
            return $ret;
        }catch (Exception $ex){
            $this->getLogger()->error(sprintf('remove error:',$this->getCollectionName(), $ex->getMessage()), $data);
            throw new AppException(Constants::CODE_MONGO,'',array(),$ex);
        }
    }

    public function update($data, $find = null,$options=array())
    {
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
        if(strpos(join('',array_keys($data)),'$') === false){
            $updateStatement=array('$set'=>$data);
        }else{
            $updateStatement=$data;
        }
        if(isset($updateStatement['$set'])){
            $this->validate($updateStatement['$set']);
        }
        try{
            $ret= $this->getCollection()->update($find, $updateStatement, $options);
            $this->getLogger()->info(sprintf('update %s success',$this->getCollectionName()),array('find'=>$find,'stmt'=>$updateStatement,'op'=>$options));
            $this->clearCache();
            return $ret;
        }catch (Exception $ex){
            $this->getLogger()->error(sprintf('update %s error:%s',$this->getCollectionName(), $ex->getMessage()), array('data'=>$data,'options'=>$options));
            throw new AppException(Constants::CODE_MONGO,'',array(),$ex);
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
                throw new AppException(Constants::CODE_MONGO,'',array(),$ex);
            }
        }else{
            $returns=array();
            foreach($data as $v){
                array_push($returns,$this->save($v, false));
            }
            return $returns;
        }
    }
}