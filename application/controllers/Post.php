<?php
/**
 * User: wangfeng
 * Date: 13-8-5
 * Time: 下午3:07
 */
class PostController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'remove'
                || $this->getRequest()->getActionName()=='update'){
            $pid = intval($this->getRequest()->getRequest('id',0));
            $post = PostModel::getInstance()->fetchOne(array('_id' => $pid));
            if($post['uid'] !== $this->userId){
                throw new AppException(Constants::CODE_NOT_FOUND_POST);
            }
        }else if(strcasecmp($this->getRequest()->getActionName(),'removeReply')===0){
            $replyId = intval($this->getRequest()->getRequest('id', 0));
            $replyInfo=ReplyModel::getInstance()->fetchOne(array('_id'=>$replyId));
            if($replyInfo['uid'] !== $this->userId){
                if($replyInfo['tp'] == Constants::FEED_TYPE_PROJECT){
                    if(0 === ProjectModel::getInstance()->count(array('_id' => $replyInfo['pid'] ))){
                        throw new AppException(Constants::CODE_NOT_FOUND_REPLY);
                    }
                }else{
                    if(0 === PostModel::getInstance()->count(array('_id'=>$replyInfo['pid'],'uid'=>$this->userId))){
                        throw new AppException(Constants::CODE_NOT_FOUND_REPLY);
                    }
                }
            }
        }else if($this->getRequest()->getActionName() == 'index'
            || strcasecmp($this->getRequest()->getActionName(),'getReplies') === 0){
            return true;
        }
        if($this->getRequest()->getActionName() == 'add'){
            if(!$this->getRequest()->isPost()){
                return false;
            }
        }
        return parent::validateAuth();
    }

    public function getRepliesAction()
    {
        $pid = intval($this->getRequest()->getRequest('pid', 0));
        $page = $this->getRequest()->getRequest('page',1);
        $pageSize = $this->getRequest()->getRequest('pageSize', Constants::LIST_REPLY_SIZE);
        $replies = ReplyModel::getInstance()->fetch(
            MongoQueryBuilder::newQuery()->query(array('pid' => $pid,'s'=> Constants::STATUS_NEW))
                ->skip(($page-1)*$pageSize)
                ->limit($pageSize)
                ->sort(array('c_t'=>-1))
                ->build()
        );

        $this->dataFlow->mergeReplys($replies);
        $data=$this->dataFlow->flow();
        $count = ReplyModel::getInstance()->count(array('pid' => $pid));
        $data['reply_count']=$count;

        // mode = 1 , render template "post-ajax-reply.twig"
        // else render template "replys.twig"

        $mode = $this->getRequest()->getRequest('mode',0);
        $this->assign( $data );
        $this->render_ajax(Constants::CODE_SUCCESS, '', null ,
            $mode == 1 ? "ajax-tpl/post-ajax-reply.twig" : "ajax-tpl/reply-items.twig" , $data );
        return false;
    }

    public function indexAction($type,$id)
    {
        $type = strtolower($type);
        $id = intval($id);
        $puid = null;

        $user = $this->user;
        if( $type==Constants::FEED_TYPE_PROJECT ){
            $this->dataFlow->pids[]=$id;
            // update view count
            $prjectModel = ProjectModel::getInstance();
            $project = $prjectModel->fetchOne(array('_id'=>$id));
            $project['vc'] ++;
            $prjectModel->updateProject( $project );

            $puid = $project['uid'];
            $this->dataFlow->flids[] = $user['lid'];
        }else{
            $this->dataFlow->fpoids[]=$id;
            // update post view count
            $postModel = PostModel::getInstance();
            $post = $postModel->fetchOne(array('_id'=>$id));
            $post['vc'] ++;
            $postModel->updatePost( $post );

            $puid = $post['uid'];
            // render location path
            $this->dataFlow->flids[] = $post['lid'];
        }

        // get reply users
        $user_list = array($puid);
        $replys = ReplyModel::getInstance()->fetch(
            MongoQueryBuilder::newQuery()
                ->query(array('pid'=>$id , 's'=>array('$gte'=>Constants::$STATUS_VISIBLE)))
                ->build()
            );

        $user_list = array_unique(array_merge(array_column($replys , 'uid') , $user_list));
        
        $user_list = array_unique($user_list);
        $this->assign(array('user_list'=>array_slice($user_list , 0 , 10)));
        $this->assign(array('reply_user_count'=>count($user_list)));
        $this->dataFlow->uids = array_merge($this->dataFlow->uids , $user_list );



        $this->assign(array('PID'=>$id,'TYPE'=>$type));
        // get post content
        // set feeds
        $feed = FeedModel::getInstance()->fetchOne(array('oid'=>$id));
        $feeds = array();
        $feeds[ $feed['_id'] ] = $feed;
        $this->dataFlow->mergeFeeds($feeds);

        $this->dataFlow->fuids[] = $feed['uid'];

        // get post like status
        $like = LikeModel::getInstance()->fetchOne(
            array(
                'oid'=>$id ,
                'uid'=>$this->userId ,
                'tp'=>array('$in'=>array(Constants::LIKE_PROJECT , Constants::LIKE_POST) )
            ));
        $this->assign(array('likes'=> array_column(array($like) , null , 'oid')));

        $data=$this->dataFlow->flow();
        $this->assign($data);
    }

    public function addAction()
    {
        $postInfo=$this->getPostInfo();
        $postInfo['uid']=$this->userId;
        $postInfo['s']=Constants::STATUS_NEW;
        unset($postInfo['_id']);
        PostModel::getInstance()->addPost($postInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function removeAction()
    {
        $postId = intval($this->getRequest()->getRequest('id', 0));
        $postModel=PostModel::getInstance();
        $post = $postModel->fetchOne(array('_id' => $postId));
        $postModel->removePost($post);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function updateAction()
    {
        $postInfo=$this->getPostInfo();
        $postModel=PostModel::getInstance();

        $postModel->updatePost($postInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function addReplyAction()
    {
        $replyModel=ReplyModel::getInstance();
        $replyInfo = $replyModel->format($this->getRequest()->getRequest(), true);
        $replyInfo['uid']=$this->userId;

        $replyInfo['s']=Constants::STATUS_NEW;
        unset($replyInfo['_id']);
        $reply = $replyModel->addReply($replyInfo);

        $replys = array();
        $replys[ $reply['_id'] ] = $reply;
        // render data
        $this->assign( $this->dataFlow->flow() );

        $this->render_ajax(Constants::CODE_SUCCESS , '' , '' , "ajax-tpl/reply-items.twig" , array('REPLYS' => $replyModel->formats($replys , true) ) );
        return false;
    }

    public function removeReplyAction()
    {
        $replyModel=ReplyModel::getInstance();
        $replyId = intval($this->getRequest()->getRequest('id',0));
        $replyInfo=$replyModel->fetchOne(array('_id'=>$replyId));
        $replyModel->removeReply($replyInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
}