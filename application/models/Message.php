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
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE),
        );
    }

    public function sendMessage($uid,$tid,$content,$time=null)
    {
        if($time==null){
            $time = new MongoDate(time());
        }
        $data=array(
            '_id'=>$this->getNextId(),
            'uid'=>intval($uid),
            'tid'=>intval($tid),
            'c'=>$content,
            'c_t'=>$time,
        );
        return $this->insert($data);
    }

    public function sendSystemMessage($tid,$content,$time=null)
    {
        return $this->sendMessage(Constants::VUID_SYSTEM, $tid, $content, $time);
    }
}