<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:25
 */
abstract class BaseModel
{
    use Strategy_Singleton;

    public function getCollectionName()
    {
        $realClassName = get_class($this);
        $realClassName = preg_replace('/(.+)Model/i', '$1', $realClassName);
        return strtolower($realClassName);
    }

    /**
     * @return MongoCollection
     */
    public function &getCollection()
    {
        $mongo = AppMongo::getInstance(Constants::CONN_MONGO_STRING);
        return $mongo->selectCollection(Constants::DB_LEPEI, $this->getCollectionName());
    }

    public function count($condition = array())
    {
        return $this->getCollection()->count($condition);
    }

    public function &fetch($condition = array(),$fields=array())
    {
        $cursor = $this->getCollection()->find($condition,$fields);
        $datas = array();
        foreach ($cursor as $data) {
            $datas[] = $data;
        }
        return $this->format($data);
    }

    public function &format(&$data)
    {
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
        if(isset($data['_id'])){
            $data = array('_id' => $data['_id']);
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
        if(empty($options) && $findById){
            $options=array('multiple'=>true);
        }
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
        if(!empty($data)){
            throw new AppException(Constants::CODE_INVALID_MODEL);
        }
    }
}