<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:15
 *@method static FeedModel getInstance()
 */
class FeedModel extends  BaseModel
{
    use Strategy_Singleton;

    public function __construct()
    {
        $this->ensureIndex(array('oid'=>1,'tp'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
        $this->ensureIndex(array('lpt'=>1,'r_t'=>-1));
//        $this->ensureIndex(array('lpt'=>1,'r_t'=>-1));
    }

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            //type post:post_id project:project_id
            'oid'=>new Schema('oid',Constants::SCHEMA_INT),
            //author uid
            'uid'=>new Schema('uid',Constants::SCHEMA_INT),
            'lpt'=>new Schema('lpt',Constants::SCHEMA_INT),
            //type maybe post,qa,project,message
            'tp'=>new Schema('type',Constants::SCHEMA_INT),
            's'=>new Schema('status',Constants::SCHEMA_INT,AppValidators::newStatusValidators()),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE),
            'r_t'=>new Schema('reply_time',Constants::SCHEMA_DATE),
            //last reply id
            'l_r'=>new Schema('last_reply_id',Constants::SCHEMA_INT)
        );
    }

    /**
     *
     * @param $oid
     * @param $uid
     * @param $lid
     * @param $type
     * @param $status
     */
    public function saveFeed($oid,$type,$uid,$lid,$status,$last_reply_time=null,$last_reply_id=null)
    {
        $feed = $this->fetchOne(array('oid' => $oid, 'tp' => $type));
        if(empty($feed)){
            $feed['_id']=$this->getNextId();
            $feed['c_t'] = new MongoDate(time());
            $feed['r_t'] = $feed['c_t'];
        }
        if(!is_null($oid)){
            $feed['oid']=$oid;
        }
        if(!is_null($type)){
            $feed['tp']=$type;
        }
        if(!is_null($uid)){
            $feed['uid']=$uid;
        }
        if(!is_null($status)){
            $feed['s']=$status;
        }
        if(!is_null($lid)){
            $loc = LocationModel::getInstance()->fetchOne(array('_id' => $lid));
            $feed['lpt']=$loc['pt'];
            $feed['lpt'][]=$lid;
        }
        if(!is_null($last_reply_time)){
            $feed['r_t']=$last_reply_time;
        }
        if(!is_null($last_reply_id)){
            $feed['l_r']=$last_reply_id;
        }
        $this->save($feed);
    }
}