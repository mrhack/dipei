<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:25
 */
abstract class BaseModel
{
    use Strategy_Singleton;
    use AppComponent;

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
        return $this->getCollection()->count($condition);
    }

    public function fetchOne($condition=array(),$fields=array()){
        return array_shift($this->fetch($condition, $fields));

    }

    public function &fetch($condition = array(),$fields=array())
    {
        $cursor = $this->getCollection()->find($condition,$fields);
        $datas = array();
        foreach ($cursor as $data) {
            $datas[] = $data;
        }
        return $datas;
    }

    /**
     */
    public function getFormatSchema(){

    }

    public function &__formatSchema(&$data,$formatSchema){
        $formated=array();
        foreach($formatSchema as $fromK=>$toK){
            if(isset($data[$fromK])){
                if(is_array($toK)){
                    $formated[$toK[0]] = $data[$fromK];
                    $formated[$toK[0]]=$this->__formatSchema($data[$fromK],$toK);
                }else if(isset($data[$fromK])){
                    $formated[$toK] = $data[$fromK];
                }
            }
        }
        return $formated;
    }

    public function &format(&$data)
    {
        if(is_array($data)){
            $formated = $this->__formatSchema($data,$this->getFormatSchema());
            return $formated;
        }else{
            return $data;
        }
    }


    public function &deFormat(&$data){
        throw new AppException(Constants::CODE_NO_IMPLEMENT);
        return $data;
    }

    public function insert($data, $batch = false)
    {
        if (!$batch) {
            $this->validate($data);
            return $this->getCollection()->insert($data);
        } else {
            foreach ($data as $v) {
                $this->validate($v);
            }
            return $this->getCollection()->batchInsert($data);
        }
    }

    public function remove($data)
    {
        if(empty($data)){
            throw new AppException(Constants::CODE_REMOVE_NEED_WHERE);
        }
        return $this->getCollection()->remove($data);
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
        return $this->getCollection()->update($find, array('$set' => $data), $options);
    }

    public function save($data, $batch = false)
    {
        if (!$batch) {
            $this->validate($data);
            return $this->update($data, null, array('upsert'=>true));
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