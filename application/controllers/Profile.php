<?php
/**
 * User: wangfeng
 * Date: 13-6-29
 * Time: 下午3:00
 */
class ProfileController extends BaseController
{
    public function validateAuth()
    {
        if( !$this->userId ) return false;
        switch ($this->getRequest()->getActionName()) {
            case 'index':
                $type = $this->getRequest()->getParam('type', 'guest');
                if($type=='host' && !$this->isLepei()){
                    return false;
                } else {
                    return true;
                }
                break;
            case 'sendMessage':
                $tid=$this->getRequest()->getRequest('tid');
                if(!UserModel::getInstance()->isValidId($tid)){
                    throw new AppException(Constants::CODE_PARAM_INVALID);
                }
                break;
            case 'newMsg':
                return !empty($this->userId);
                break;
            default:
                return parent::validateAuth();
                break;
        }
        
    }

    private function getLikeOids( $type , $page )
    {
        $likeModel = LikeModel::getInstance();
        $likes = $likeModel->fetch(
            MongoQueryBuilder::newQuery()
                ->query(array('uid'=>$this->userId,'tp'=>is_array($type)? array('$in'=>$type) : $type ))
                ->sort(array('t'=>-1))
                ->skip( ($page-1) * Constants::LIST_PAGE_SIZE )
                ->limit(Constants::LIST_PAGE_SIZE)
                ->build()
        );
        $oids=array();
        // assign like objects
        $likeObjs = array();
        foreach($likes as $like){
            $oids[] = $like['oid'];
            $likeObjs[ $like['oid'] ] = $like;
        }

        $this->assign(array( "likes" => $likeModel->formats( $likeObjs , true ) ) );
        return $oids;
    }

    public function msgModule()
    {
        // get msg from db
        $msgModel = MessageModel::getInstance();
        $tid = intval($this->getRequest()->getRequest('tid'));

        $page = $this->getPage();
        if( empty( $tid ) ){
            $query = MongoQueryBuilder::newQuery()
                ->query(array(
                    'uid' => array('$gt'=> 0),
                    '$or'=>array(
                        array('uid'=>$this->userId , 'us'=>Constants::STATUS_NEW),
                        array('tid'=>$this->userId , 'ts'=>Constants::STATUS_NEW)
                    )
                ))
                ->sort(array('c_t'=>-1))
                ->build();
            $msgs = $msgModel->fetch( $query );
            $msgGroup = array();
            $msgGroupNum = array();
            $userIdList = array();
            foreach ($msgs as $msg) {
                if( $msg['uid'] == $this->userId ){
                    $tid = $msg['tid'];
                } else {
                    $tid = $msg['uid'];
                }
                if( !isset( $msgGroup[$tid] ) ){
                    $msgGroup[$tid] = $msg;
                    $userIdList[] = $tid;
                    $msgGroupNum[$tid] = 1;
                } else {
                    $msgGroupNum[$tid] ++ ;
                }
            }
            $msgs = array_slice($msgGroup, ( $page - 1) * Constants::LIST_PAGE_SIZE , Constants::LIST_PAGE_SIZE);

            $this->assign(array("msgs_num" => $msgGroupNum));
            $this->assign($this->getPagination($page,
                Constants::LIST_PAGE_SIZE,
                count($msgGroup)));
        } else {
            $userIdList = array( $tid );
            $query = MongoQueryBuilder::newQuery()
                ->query(array(
                    // filter for notice
                    'uid' => array('$gt'=> 0),
                    '$or'=>array(
                        array('uid'=>$this->userId , 'tid'=> $tid , 'us'=>Constants::STATUS_NEW),
                        array('tid'=>$this->userId , 'uid'=> $tid , 'ts'=>Constants::STATUS_NEW)
                    )
                ))
                ->sort(array('c_t'=>-1))
                ->skip(($page-1) * Constants::LIST_PAGE_SIZE)
                ->limit(Constants::LIST_PAGE_SIZE)
                ->build();
            $msgModel->update(array('r'=>1),array('$or'=>array(array('uid'=>$this->userId,'tid'=>$tid),array('tid'=>$this->userId,'uid'=>$tid))),array('multiple'=>true));
            $msgs = $msgModel->fetch( $query );
            $this->assign($this->getPagination($page,
                Constants::LIST_PAGE_SIZE,
                $msgModel->count(array(
                '$or'=>array(
                    array('uid'=>$this->userId , 'tid'=> $tid , 'us'=>Constants::STATUS_NEW),
                    array('tid'=>$this->userId , 'uid'=> $tid , 'ts'=>Constants::STATUS_NEW)
                )
            ))));
        }
        $msgUsers=UserModel::getInstance()->fetch(
            MongoQueryBuilder::newQuery()
                ->query(array(
                    '_id' => array(
                        '$in'=>$userIdList
                    )
                )
            )
                ->build()
        );
        $this->dataFlow->mergeUsers( $msgUsers );

        $this->assign(array("msgs"=> $msgModel->formats($msgs , true)));
        UserModel::getInstance()->clearCount($this->userId,'msgs.m');

    }

    public function indexAction($type,$module)
    {
        $type = strtolower($type);
        $this->assign(array('TYPE'=>$type,'MODULE'=>$module));
        $page = $this->getRequest()->getRequest('page',1);
        $count = 0;
        switch( strval($module) ){
            case "wish-users":
                $uids=$this->getLikeOids(Constants::LIKE_USER , $page);
                $this->assign(array('wish_users'=>$uids));
                $this->dataFlow->fuids = array_merge($this->dataFlow->fuids, $uids);
                $count = LikeModel::getInstance()->count(
                    MongoQueryBuilder::newQuery()
                        ->query(array('uid'=>$this->userId,'tp' => Constants::LIKE_USER))
                        ->build()
                        );
                break;
            case "wish-location":
                $locs=$this->getLikeOids(Constants::LIKE_LOCATION , $page);
                $this->assign(array('wish_location'=>$locs));
                $this->dataFlow->flids = array_merge($this->dataFlow->flids, $locs);
                $count = LikeModel::getInstance()->count(
                    MongoQueryBuilder::newQuery()
                        ->query(array('uid'=>$this->userId,'tp' => Constants::LIKE_LOCATION))
                        ->build()
                        );
                break;
            case "wish-post":
                $types = array(Constants::LIKE_PROJECT,Constants::LIKE_POST);
                $pids = $this->getLikeOids($types , $page);
                $this->assign(array('wish_post' => $pids));

                $feedModel = FeedModel::getInstance();
                $feeds = $feedModel->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array('oid'=>array('$in'=>$pids)))
                        ->build()
                        );
                $this->dataFlow->mergeFeeds( $feeds );
                $feeds = $feedModel->formats( $feeds , true );
                $feeds = array_column( $feeds , null , 'oid');
                $this->assign(array('like_feeds'=>$feeds ));
                
                
                $count = LikeModel::getInstance()->count(
                    MongoQueryBuilder::newQuery()
                        ->query(array('uid'=>$this->userId,'tp' => array('$in'=> $types)))
                        ->build()
                        );
                break;
            case "msg":
                $this->msgModule();
                break;
            case "notice":
                $messageModel=MessageModel::getInstance();
                $query=array('uid' => Constants::VUID_SYSTEM, 'tid' => $this->userId);
                $messages=$messageModel->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query($query)
                        ->sort(array('c_t'=>-1))
                        ->limit(Constants::LIST_PAGE_SIZE)
                        ->skip(($page-1) * Constants::LIST_PAGE_SIZE )
                        ->build());
                $this->dataFlow->mergeMessages($messages);

                $count = $messageModel->count($query);
                UserModel::getInstance()->clearCount($this->userId,'msgs.s');

                break;
            // received replies
            case "reply":
                $replies = ReplyModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array(
                            '$or'=>array(
                                array('tid' => $this->userId ,),
                                array('ruid' => $this->userId ,),
                                ),
                            'uid' => array('$ne'=> $this->userId) ,
                            's'=>array('$gte'=>Constants::$STATUS_VISIBLE)))
                        ->skip(($page-1) * Constants::LIST_REPLY_SIZE)
                        ->sort(array('c_t'=>-1))
                        ->limit( Constants::LIST_REPLY_SIZE )
                        ->build()
                );
                $this->dataFlow->mergeReplys($replies);
                $count = ReplyModel::getInstance()->count(array('tid' => $this->userId , 'uid' => array('$ne'=> $this->userId)));
                $this->assign(array('reply_count'=>$count));
                // get reply's posts
                $feeds=FeedModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array("oid" => array('$in' => array_unique(array_column($replies , "pid")))))
                        ->build()
                );
                $this->dataFlow->mergeFeeds($feeds);
                UserModel::getInstance()->clearCount($this->userId,'msgs.r');
                break;
            // send replies
            case "out-reply":
                $replies = ReplyModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array(
                            'uid' => $this->userId ,
                            'tid' => array('$ne'=> $this->userId) ,
                            's'=>array('$gte'=>Constants::STATUS_NEW)))
                        ->skip(($page-1) * Constants::LIST_REPLY_SIZE)
                        ->sort(array('c_t'=>-1))
                        ->limit( Constants::LIST_REPLY_SIZE )
                        ->build()
                );
                $this->dataFlow->mergeReplys($replies);
                $count = ReplyModel::getInstance()->count(array('uid' => $this->userId , 'tid' => array('$ne'=> $this->userId)));
                $this->assign(array('reply_count'=>$count));
                // get reply's posts
                $feeds=FeedModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array("oid" => array('$in' => array_unique(array_column($replies , "pid")))))
                        ->build()
                );
                $this->dataFlow->mergeFeeds($feeds);
                break;
            case "setting":
                $this->dataFlow->lids = array_merge($this->dataFlow->lids , array($this->user['ctr']));
                break;
        }
        if( $count > 0 ){
            $this->assign($this->getPagination($page,Constants::LIST_PAGE_SIZE,$count));
        }
        $this->getView()->assign($this->dataFlow->flow());
    }

    public function removeProjectAction()
    {
        $pid=intval($this->getRequest()->getPost('pid',0));
        $projectModel=ProjectModel::getInstance();
        $project=$projectModel->fetchOne(array('_id' => $pid, 'uid' => $this->userId));
        if(empty($project)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        $projectModel->removeProject($project);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function addProjectAction()
    {
        $projectModel=ProjectModel::getInstance();
        $projectInfo = $this->getProjectInfo();
        unset($projectInfo['_id']);//
        $projectInfo['uid']=$this->userId;
        $projectModel->addProject($projectInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function updateProjectAction()
    {
        $projectModel=ProjectModel::getInstance();
        $projectInfo = $this->getProjectInfo();
        if($projectModel->fetchOne(array('_id'=>$projectInfo['_id'],'uid'=>$this->userId))){
            $projectModel->updateProject($projectInfo);
            $this->render_ajax(Constants::CODE_SUCCESS);
        }else{
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        return false;
    }


    public function settingAction()
    {
        if($this->getRequest()->isPost()){
            $userModel=UserModel::getInstance();
            $userInfo = array_merge($this->user,$userModel->format($this->getRequest()->getRequest(), true));
            unset($userInfo['n']);
            $userInfo['_id']=$this->userId;
            $userModel->updateUser($userInfo);
        }
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function newMsgAction()
    {
        $this->user['o_t']=new MongoDate();
        UserModel::getInstance()->update($this->user);
        $user=UserModel::getInstance()->format($this->user);
        $this->render_ajax(Constants::CODE_SUCCESS, '', array('messages'=>$user['messages']));
        return false;
    }

    public function removeMessageAction(){
        $messageModel=MessageModel::getInstance();
        $id = intval( $this->getRequest()->getRequest('id') );
        $msg=$messageModel->fetchOne(array(
            '_id' => $id,
            '$or' => array(
                array('uid' => $this->userId),
                array('tid' => $this->userId),
                )
            ));
        if(empty($msg)){
            throw new AppException(Constants::CODE_NOT_FOUND_MESSAGE);
        }
        $messageModel->removeMessage($msg , $this->userId);
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }
    public function removeUserMessageAction(){
        $messageModel = MessageModel::getInstance();
        $tid = intval( $this->getRequest()->getRequest('tid') );
        $messageModel->update(array(
            '$set'=>array( 'us'=> Constants::STATUS_DELETE ),
            ) , array(
            'uid' => $this->userId,
            'tid' => $tid
            ) , array(
            'multi'=> true
            ));
        $messageModel->update(array(
            '$set'=>array( 'ts'=> Constants::STATUS_DELETE ),
            ) , array(
            'tid' => $this->userId,
            'uid' => $tid
            ) , array(
            'multi'=> true
            ));
        $this->render_ajax(Constants::CODE_SUCCESS);
        return false;
    }

    public function sendMessageAction()
    {
        $messageModel=MessageModel::getInstance();
        $message = $messageModel->format($this->getRequest()->getPost(),true);
        
        $message['uid'] = $this->userId;
        $r = $messageModel->sendMessage($message['uid'], $message['tid'], $message['c']);
        
        // render to data
        $message['c_t'] = new MongoDate(time());
        $message['_id'] = $r['inserted'];
        $message = $messageModel->formats( array($message) , true );

        $data=$this->dataFlow->flow();
        $data['msgs'] = $message;
        $this->render_ajax( Constants::CODE_SUCCESS , '' , '' , 'profile/w/_p-msg-items.twig' , $data );
        return false;
    }


    public function resetPWAction(){
        $md5Opw = md5($this->getRequest()->getRequest('opw'));
        $newpw = $this->getRequest()->getRequest('password');
        $cnewpw = $this->getRequest()->getRequest('confirm-password');
        $user = UserModel::getInstance()->fetchOne(array("_id"=>$this->userId));
        if( $user['pw'] != $md5Opw ){
            throw new AppException(Constants::CODE_PASSWORD_NOT_RIGHT);
        }
        if( preg_match_all("/./", $newpw ) < 6 ){
            throw new AppException(Constants::CODE_PASSWORD_TOO_SHORT);
        }
        $user['pw'] = md5( $newpw );
        UserModel::getInstance()->updateUser( $user ); 
        $this->render_ajax( Constants::CODE_SUCCESS );
        return false;
    }
}