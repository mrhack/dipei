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
        }else if(strcmp($this->getRequest()->getActionName(),'removeReply')===0){
            $replyId = intval($this->getRequest()->getRequest('id', 0));
            $replyInfo=ReplyModel::getInstance()->fetchOne(array('_id'=>$replyId));
            if($replyInfo['uid'] !== $this->uid){
                throw new AppException(Constants::CODE_NOT_FOUND_REPLY);
            }
            if($replyInfo['tp'] == Constants::FEED_TYPE_PROJECT){
                if(0 === ProjectModel::getInstance()->count(array('_id' => $replyInfo['pid'], 'uid' => $this->userId))){
                    throw new AppException(Constants::CODE_NOT_FOUND_REPLY);
                }
            }else{
                if(0 === PostModel::getInstance()->count(array('_id'=>$replyInfo['pid'],'uid'=>$this->userId))){
                    throw new AppException(Constants::CODE_NOT_FOUND_REPLY);
                }
            }
        }else if($this->getRequest()->getActionName() == 'index'
            || strcmp($this->getRequest()->getActionName(),'getReplies') === 0){
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
            MongoQueryBuilder::newQuery()->query(array('pid' => $pid))->skip(($page-1)*$pageSize)->limit($pageSize)->build()
        );
        $this->dataFlow->mergeReplys($replies);
        $data=$this->dataFlow->flow();
        $count = ReplyModel::getInstance()->count(array('pid' => $pid));
        $data['reply_count']=$count;

        // mode = 1 , render template "post-ajax-reply.twig"
        // else render template "replys.twig"

        $mode = $this->getRequest()->getRequest('mode',1);
        $this->render_ajax(Constants::CODE_SUCCESS, '', null ,
            $mode == 1 ? "ajax-tpl/post-ajax-reply.twig" : "ajax-tpl/replys.twig" , $data );
        return false;
    }

    public function indexAction($type,$id)
    {
        $type = strtolower($type);
        $id = intval($id);
        if($type=='project'){
            $this->dataFlow->pids[]=$id;
        }else{
            $this->dataFlow->poids[]=$id;
        }
        $this->assign(array('PID'=>$id,'TYPE'=>$type));
        $this->assign($this->dataFlow->flow());
        $this->dump();
        return false;
    }

    public function addAction()
    {
        $postInfo=$this->getPostInfo();
        $postInfo['uid']=$this->userId;
        $postInfo['s']=Constants::STATUS_NEW;
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
        $replyModel->addReply($replyInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
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