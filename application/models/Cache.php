<?php
/**
 * User: wangfeng
 * Date: 13-8-31
 * Time: 下午10:02
 *
 * mongo K-V cache
 *
 * @method static CacheModel getInstance()
 */
class CacheModel extends BaseModel
{
    use Strategy_Singleton;

    public function __construct()
    {
        $this->ensureIndex(array('k'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
    }

    public function getSchema()
    {
        //free schema
        return array();
    }

    public function setMulti($keys,$vals)
    {
        foreach($keys as $i=>$key){
            $this->set($key, $vals[$i]);
        }
    }

    public function getMulti($keys)
    {
        return $this->fetch(array('k'=>array('$in'=>$keys)));
    }

    public function set($key,$val)
    {
        $r = $this->fetchOne(array('k' => $key));
        if(isset($r['_id'])){
            $this->save(array('_id'=>$r['_id'],'k' => $key, 'v' => $val));
        }else{
            $this->insert(array('k' => $key, 'v' => $val));
        }
    }

    public function get($key){
        $r= $this->fetchOne(array('k' => $key));
        return $r['v'];
    }
}