<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:30
 * @method static ReplyModel getInstance()
 */
class ReplyModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('_id',Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            'pid'=>new Schema('pid',Constants::SCHEMA_INT),
            'rid'=>new Schema('rid',Constants::SCHEMA_INT),//reply-reply id
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE)
        )//index
            +array(
            //save to post uid
            'tid'=>new Schema('tid',Constants::SCHEMA_INT)
        );
    }

    public function addReply($replyInfo){
        if(!isset($replyInfo['uid']) || !isset($replyInfo['pid']) || !isset($replyInfo['s']) || !isset($replyInfo['c'])){
            throw new AppException(Constants::CODE_LACK_FIELD);
        }
        if(!isset($replyInfo['_id'])){
            $replyInfo['_id']=$this->getNextId();
        }
        if(!isset($replyInfo['c_t'])){
            $replyInfo['c_t'] = new MongoDate(time());
        }
        $this->saveReply($replyInfo);
        //update feed last reply
        $postInfo=PostModel::getInstance()->fetchOne(array("_id"=>['pid']));
        FeedModel::getInstance()->saveFeed($postInfo['_id'], $postInfo['tp'], $postInfo['uid'], $postInfo['lid'], $postInfo['s'], $replyInfo['c_t'] , $replyInfo['_id']);
        //update post last reply
        $postInfo['r_t'] = $replyInfo['c_t'];
        $postInfo['r_c']++;
        PostModel::getInstance()->updatePost($postInfo);
        return $replyInfo['_id'];
    }

    public function removeReply($replyInfo){
        $replyInfo['s']=Constants::STATUS_DELETE;
        $this->saveReply($replyInfo);
    }

    public function updateReply($replyInfo){
        $this->saveReply($replyInfo);
    }

    public function saveReply($replyInfo)
    {
        if(isset($replyInfo['pid'])){
            $postInfo=PostModel::getInstance()->fetchOne(array("_id"=>['pid']));
            if(isset($postInfo['uid'])){
                $replyInfo['tid'] = $postInfo['uid'];
            }
        }
        $this->save($replyInfo);
    }

}