<?php
/**
 * User: wangfeng
 * Date: 13-8-3
 * Time: 下午9:24
 * @method static PostModel getInstance()
 */
class PostModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('_id',Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            'tp'=>new Schema('type',Constants::SCHEMA_INT,array(
                AppValidators::newRange(array(Constants::FEED_TYPE_POST,Constants::FEED_TYPE_QA))
            )),
            'lid'=>new Schema('lid',Constants::SCHEMA_INT),
            't' => new Schema('title', Constants::SCHEMA_STRING, array(
                AppValidators::newLength(array('$le' => 100), _e('标题不得超过100字')),
            )),
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
            'vc'=>new Schema('view_count',Constants::SCHEMA_INT),
            'lk' => new Schema('like', Constants::SCHEMA_INT),
            'c_t'=> new Schema('create_time',Constants::SCHEMA_DATE),
        );
    }

    public function addPost($postInfo)
    {
        if(!isset($postInfo['tp']) || !isset($postInfo['lid']) || !isset($postInfo['s']) || !isset($postInfo['t'])){
            throw new AppException(Constants::CODE_LACK_FIELD);
        }
        if(!isset($postInfo['_id'])){
            $postInfo['_id']=ProjectModel::getInstance()->getNextId();
        }
        $this->savePost($postInfo);
        return $postInfo['_id'];
    }

    public function removePost($postInfo)
    {
        if(!isset($postInfo['tp'])){
            throw new AppException(Constants::CODE_LACK_FIELD);
        }
        $postInfo['s']=Constants::STATUS_DELETE;
        $this->savePost($postInfo);
    }

    public function updatePost($postInfo){
        $this->savePost($postInfo);
    }

    public function savePost($postInfo)
    {
        $this->save($postInfo);
        //save feed
        FeedModel::getInstance()->saveFeed($postInfo['_id'], $postInfo['tp'], $postInfo['uid'], $postInfo['lid'],$postInfo['s']);
    }

}