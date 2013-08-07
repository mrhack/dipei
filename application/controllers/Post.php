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
        }else if($this->getRequest()->getActionName() == 'removeReply'){
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
        }else if($this->getRequest()->getActionName() == 'index'){
            return true;
        }
        if($this->getRequest()->getActionName() == 'add'
            || $this->getRequest()->getActionName() == 'addReply'){
            if(!$this->getRequest()->isPost()){
                return false;
            }
        }
        return parent::validateAuth();
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