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
        if($this->getRequest()->getActionName() == 'index'){
            $type = $this->getRequest()->getParam('type', 'guest');
            if($type=='host' && !$this->isLepei()){
                return false;
            }
        }else if($this->getRequest()->getActionName() == 'sendMessage'){
            $tid=$this->getRequest()->getRequest('tid');
            if(!UserModel::getInstance()->isValidId($tid)){
                throw new AppException(Constants::CODE_PARAM_INVALID);
            }
        }
        return parent::validateAuth();
    }

    private function getLikeOids( $type , $page )
    {
        $likeModel = LikeModel::getInstance();
        $likes = $likeModel->fetch(
            MongoQueryBuilder::newQuery()
                ->query(array('uid'=>$this->userId,'tp'=>$type))
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
            $msgs = $msgModel->fetch( $query );
            $this->assign($this->getPagination($page,
                Constants::LIST_PAGE_SIZE,
                $msgModel->count(array(
                '$or'=>array(
                    array('uid'=>$this->userId , 'us'=>Constants::STATUS_NEW),
                    array('tid'=>$this->userId , 'ts'=>Constants::STATUS_NEW)
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
        
    }

    // public function sysMsgModule()
    // {
    //     $messageModel=MessageModel::getInstance();
    //     $query=array('uid' => Constants::VUID_SYSTEM, 'tid' => $this->userId);
    //     $messages=$messageModel->fetch(
    //         MongoQueryBuilder::newQuery()
    //             ->query($query)
    //             ->sort(array('c_t'=>-1))
    //             ->limit(Constants::LIST_MESSAGE_SIZE)
    //             ->build());
    //     $this->dataFlow->mergeMessages($messages);

    //     $this->assign($this->getPagination($this->getPage(), Constants::LIST_MESSAGE_SIZE, $messageModel->count($query)));

    //     $this->assign($this->dataFlow->flow());
    // }

    public function indexAction($type,$module)
    {
        $type = strtolower($type);
        $this->assign(array('TYPE'=>$type,'MODULE'=>$module));
        $page = $this->getRequest()->getRequest('page',1);
        $count = 0;
        switch( $module ){
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
            case "wish-project":
                $pids = $this->getLikeOids(Constants::LIKE_PROJECT , $page);
                $this->assign(array('wish_projects' => $pids));
                $this->dataFlow->pids = array_merge($this->dataFlow->pids, $pids);
                $count = LikeModel::getInstance()->count(
                    MongoQueryBuilder::newQuery()
                        ->query(array('uid'=>$this->userId,'tp' => Constants::LIKE_PROJECT))
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

                break;
            // received replies
            case "reply":
                $replies = ReplyModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array(
                            'tid' => $this->userId ,
                            'uid' => array('$ne'=> $this->userId) ,
                            's'=>Constants::STATUS_NEW))
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
                break;
            // send replies
            case "out-reply":
                $replies = ReplyModel::getInstance()->fetch(
                    MongoQueryBuilder::newQuery()
                        ->query(array(
                            'uid' => $this->userId ,
                            'tid' => array('$ne'=> $this->userId) ,
                            's'=>Constants::STATUS_NEW))
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
            $userInfo = $userModel->format($this->getRequest()->getRequest(), true);
            unset($userInfo['n']);
            $userInfo['_id']=$this->userId;
            $userModel->updateUser($userInfo);
        }
        $this->render_ajax(Constants::CODE_SUCCESS);
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
        // TODO ... update multi records
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
}