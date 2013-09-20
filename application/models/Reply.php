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
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            'pid'=>new Schema('pid',Constants::SCHEMA_INT),
            'rid'=>new Schema('rid',Constants::SCHEMA_INT),//reply-reply id
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'c'=>new Schema('content',Constants::SCHEMA_STRING),
            'c_t'=>new Schema('create_time',Constants::SCHEMA_DATE)
        )//index
            +array(
            // save to post type
            'tp'=>new Schema('type',Constants::SCHEMA_INT,array(
                AppValidators::newRange(array(Constants::FEED_TYPE_POST,Constants::FEED_TYPE_QA , Constants::FEED_TYPE_PROJECT))
            )),
            // save to rid's user
            'ruid'=>new Schema('ruid',Constants::SCHEMA_INT),
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
        
        //update feed last reply
        if( $replyInfo['tp'] == Constants::FEED_TYPE_PROJECT ){
            $model = ProjectModel::getInstance();
        } else if ( in_array($replyInfo['tp'], array( Constants::FEED_TYPE_POST , Constants::FEED_TYPE_QA))) {
            $model = PostModel::getInstance();
        }
        $pInfo = $model->fetchOne(array("_id"=>$replyInfo['pid']));
        $feed = FeedModel::getInstance()->fetchOne(array('oid'=>$pInfo['_id']));
        $feed['l_r'] = $replyInfo['_id'];
        $feed['l_r_u'] = $replyInfo['uid'];
        FeedModel::getInstance()->updateFeed( $feed );
        //update post last reply
        $pInfo['r_t'] = $replyInfo['c_t'];
        $pInfo['r_c']++;
        if( $replyInfo['tp'] == Constants::FEED_TYPE_PROJECT ){
            $model->updateProject($pInfo);
        } else if ( in_array($replyInfo['tp'], array( Constants::FEED_TYPE_POST , Constants::FEED_TYPE_QA))) {
            $model->updatePost($pInfo);
        }
        // save post uid to reply
        $replyInfo['tid'] = $pInfo['uid'];
        $this->saveReply($replyInfo);

        // add message to author
        if( $replyInfo['uid'] != $pInfo['uid'] ){
            UserModel::getInstance()->incCount($pInfo['uid'],'msgs.r');
        }
        // add message to ruid user
        if( isset( $replyInfo['ruid'] ) && !empty($replyInfo['ruid']) 
            && $replyInfo['ruid'] != $pInfo['uid'] ){
            UserModel::getInstance()->incCount($replyInfo['ruid'],'msgs.r');
        }
        
        return $replyInfo;
    }

    public function removeReply($replyInfo){
        $replyInfo['s']=Constants::STATUS_DELETE;
        $this->saveReply($replyInfo);

        //update feed last reply
        if( $replyInfo['tp'] != Constants::FEED_TYPE_PROJECT ){
            $postInfo=PostModel::getInstance()->fetchOne(array("_id"=>$replyInfo['pid']));
            //update post last reply
            $postInfo['r_c']--;
            PostModel::getInstance()->updatePost($postInfo);
        } else {
            $projectInfo=ProjectModel::getInstance()->fetchOne(array("_id"=>$replyInfo['pid']));
            $projectInfo['r_c']--;
            ProjectModel::getInstance()->updateProject($projectInfo);
        }
    }

    public function updateReply($replyInfo){
        $this->saveReply($replyInfo);
    }

    public function saveReply($replyInfo)
    {
        $this->save($replyInfo);
    }

}