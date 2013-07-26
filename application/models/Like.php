<?php
/**
 * User: wangfeng
 * Date: 13-7-13
 * Time: 下午8:23
 * @method static LikeModel getInstance()
 */
class LikeModel extends  BaseModel
{
    use Strategy_Singleton;

    public function __construct()
    {
        $this->getCollection()->ensureIndex(array('oid'=>1,'tp'=>1,'ip'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
    }

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            'uid'=>new Schema('uid',Constants::SCHEMA_INT),
            'oid'=>new Schema('oid',Constants::SCHEMA_INT),
            'ip'=>new Schema('ip',Constants::SCHEMA_STRING),
            'am'=>new Schema('am',Constants::SCHEMA_INT),
            't'=>new Schema('time',Constants::SCHEMA_DATE),
            'tp'=>new Schema('type',Constants::SCHEMA_INT,array(
                AppValidators::newRange(Constants::$LIKE_TYPES,_e('非法喜欢type'))
            ))
        );
    }

    public function like($uid,$type,$oid,$amount=1,$time=null,$ip=null)
    {
        $uid = intval($uid);
        $type = intval($type);
        $oid = intval($oid);

        if(is_null($time)){
            $time = new MongoDate(time());
        }
        if(is_null($ip)){
            $ip = AppHelper::getInstance()->getIp();
        }

        $data=array(
            'uid'=>$uid,
            'oid'=>$oid,
            'tp'=>$type,
            't'=>$time,
            'am'=>$amount,
            'ip'=>$ip,
        );
        $ret=$this->insert($data);
        $this->_incObjectLike($oid,$type,$amount);
        return $ret['inserted'];
    }

    public function unlike($uid,$type,$oid)
    {
        $query=array('tp'=>$type,'oid'=>$oid);
        if(!empty($uid)){
            $query['uid']=$uid;
        }else{
            $query['ip'] = AppHelper::getInstance()->getIp();
        }
        $like = $this->fetchOne($query);
        if(empty($like)){
            throw new AppException(Constants::CODE_INVALID_LIKE_ID);
        }
        $ret=$this->remove(array('_id' => intval($like['_id'])));
        $this->_incObjectLike($like['oid'], $like['tp'], $like['am'] * -1);
        return $ret;
    }

    private function _incObjectLike($oid,$type,$amount)
    {
        $updateRet=null;
        switch($type){
            case Constants::LIKE_LOCATION:
                $updateRet=LocationModel::getInstance()->update(array('$inc'=>array('lk'=>$amount)),array('_id'=>$oid));
                break;
            case Constants::LIKE_PROJECT:
                $updateRet = UserModel::getInstance()->update(array('$inc'=>array('ps.$.lk'=>$amount)),array('ps._id'=>$oid));
                break;
        }
        if(empty($updateRet) || $updateRet['n'] !=1){
            throw new AppException(Constants::CODE_NOT_FOUND_LIKE_OBJECT);
        }
    }
}