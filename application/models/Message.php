<?php
/**
 * 私信
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:49
 * @method static MessageModel getInstance()
 */
class MessageModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            'uid'=>new Schema('uid',Constants::SCHEMA_INT),
            'tid'=>new Schema('tid',Constants::SCHEMA_INT),
            'c'=>new Schema('content',Constants::SCHEMA_STRING,array(
                AppValidators::newRequired(_e('私信内容不能为空')),
                AppValidators::newLength(array('$le' => 300), _e('私信不得超过300字')),
            )),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE),
            // uid user status to msg
            'us' =>new Schema('ustatus' ,Constants::SCHEMA_ARRAY, array(
                AppValidators::newRange(array(Constants::STATUS_NEW,Constants::STATUS_DELETE)))
            ),
            'r' => new Schema('read',Constants::SCHEMA_INT),
            // tid user status to msg
            'ts' =>new Schema('tstatus' , Constants::SCHEMA_ARRAY,array(
                AppValidators::newRange(array(Constants::STATUS_NEW,Constants::STATUS_DELETE)))
            ),
        );
    }

    public function sendMessage($uid,$tid,$content,$time=null)
    {
        if(empty($content) || empty($uid) || empty($tid)){
            throw new AppException(Constants::CODE_LACK_FIELD);
        }
        if($time==null){
            $time = new MongoDate(time());
        }
        $data=array(
            '_id'=>$this->getNextId(),
            'uid'=>intval($uid),
            'tid'=>intval($tid),
            'c'=>$content,
            'c_t'=>$time,
            'us'=>Constants::STATUS_NEW,
            'ts'=>Constants::STATUS_NEW,
            'r'=>0,
        );
        UserModel::getInstance()->incCount($tid, $uid==Constants::VUID_SYSTEM?'msgs.s':'msgs.m');
        return $this->insert($data);
    }

    public function removeMessage( $msg , $uid ){
        if ( $msg['uid'] == $uid ){
            $msg['us'] = Constants::STATUS_DELETE;
        } else if ( $msg['tid'] == $uid ){
            $msg['ts'] = Constants::STATUS_DELETE;
        }
        $this->save($msg);
    }

    public function sendSystemMessage($tid,$content,$time=null)
    {
        return $this->sendMessage(Constants::VUID_SYSTEM, $tid, $content, $time);
    }
}