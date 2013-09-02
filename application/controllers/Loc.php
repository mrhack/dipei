<?php
/**
 * User: wangfeng
 * Date: 13-7-6
 * Time: 下午4:08
 *
 */
class LocController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName()=='index'){
            $lid = $this->getRequest()->getParam('lid');
            if(!LocationModel::getInstance()->isValidId($lid)){
                $this->getLogger()->warn("not found lid $lid", array('request' => $this->getRequest(),'input'=>$_REQUEST));
                throw new AppException(Constants::CODE_NOT_FOUND);
            }
        }
        return true;
    }

    public function cityAction($lid)
    {
        $lid = intval($lid);
        $this->dataFlow->flids[]=$lid;

        $page=$this->getPage();

        //feed
        $type=intval($this->getRequest()->getRequest('type',0));
        if(in_array($type,Constants::$FEED_TYPES)){
            $query= array('lpt'=>$lid,'tp'=>$type);
        }else{
            $query=(array('lpt'=>$lid));
        }
        $feeds=FeedModel::getInstance()->fetch(MongoQueryBuilder::newQuery()->query($query)->sort(array('r_t' => -1))->limit(Constants::LIST_FEED_SIZE)->skip(($page-1)* Constants::LIST_FEED_SIZE)->build());
        $this->dataFlow->mergeFeeds($feeds);


        //like users go
        $likeModel=LikeModel::getInstance();
        $likeUsers=$likeModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('tp'=>Constants::LIKE_LOCATION,'oid'=>$lid))->limit(Constants::LIST_LIKE_USERS_SIZE)->build(),
            array('uid'=>true),
            Constants::INDEX_MODE_ARRAY
        );
        $likeUserIds = array();
        foreach ($likeUsers as $likeUser) {
            $likeUserIds[] = $likeUser["uid"];
        }
        $this->assign(array('like_users'=>$likeUserIds));

        $myLikeLocIds=$this->assignMyFavLocations();

        //hot lepeis
        $userModel=UserModel::getInstance();
        $hotLepeis=$userModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('lpt'=>$lid))->sort(array('v_c'=>-1))->limit(Constants::LIST_HOT_LEPEI_SIZE)->build()
        );
        $this->dataFlow->mergeUsers($hotLepeis);
        $this->assign(array('hot_lepeis'=>array_keys($hotLepeis)));

        // latest lepeis in current locatin
        $latestLepeis=$userModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('lpt'=>$lid))->sort(array('c_t'=>-1))->limit(Constants::LATEST_LEPEI_SIZE)->build()
        );
        $this->dataFlow->mergeUsers($latestLepeis);
        $this->assign(array('latest_lepeis'=>array_keys($latestLepeis)));

        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());

        $this->assign($this->getPagination($page,Constants::LIST_FEED_SIZE,FeedModel::getInstance()->count($query)));

        if(!empty($this->user) && in_array($lid,$myLikeLocIds)){
            //update loc view time
            $this->user['l_vts'][$lid]=new MongoDate();
            $userModel->save($this->user);
        }

        // assign like post status
        $feedIds = array_column($feeds , 'oid');
        $likes = LikeModel::getInstance()->fetch(
            MongoQueryBuilder::newQuery()
                ->query(
                    array('uid'=>$this->userId ,
                        '$or'=> array(
                            array(
                            'oid'=>array('$in'=> $feedIds ),
                            'tp'=> array('$in'=> array( Constants::LIKE_POST , Constants::LIKE_PROJECT ))
                            ),
                            array(
                            'oid'=> $lid,
                            'tp'=> Constants::LIKE_LOCATION
                                )
                        ),
                        )
                    )
                ->build()
            );
        $this->assign(array('likes'=> array_column( $likes , null , 'oid' )));
    }

    public function indexAction($lid)
    {
        // for history lepei
        $this->assignViewedLepei();
        $lid = intval($lid);
        $locationModel=LocationModel::getInstance();
        //
        $page=$this->getPage();
        $this->dataFlow->flids[]=$lid;
        $location = $locationModel->fetchOne(array('_id' => $lid));
        if($locationModel->isCountry($location)){
            //render city
            $locationModel=LocationModel::getInstance();
            $cities = $locationModel->fetch(
                MongoQueryBuilder::newQuery()->query(array('pt.1' => $lid))->sort(array('c.p' => -1))->limit(5)->build()
            );
            $this->assign(array('loc_list' => array_keys($cities)));
            $this->dataFlow->mergeLocations($cities);

            //render child loc_list
            $childs=$locationModel->fetch(
                MongoQueryBuilder::newQuery()->query(array('pt'=>$lid))->sort(array('c.d'=>-1))->limit(20)->build()
            );
            $this->assign(array('child_loc_list' => array_keys($childs)));
            $this->dataFlow->mergeLocations($childs);

            //render new lepei
            $userModel=UserModel::getInstance();
            $query = array('lpt' => $lid );
          
            $users=$userModel->fetch(
                MongoQueryBuilder::newQuery()->query($query)->limit(Constants::LIST_LOC_USER_SIZE)->build()
            );
            $this->assign(array('lepei_list'=>array_keys($users)));
            $this->dataFlow->mergeUsers($users);

            $this->assign($this->getPagination($page, Constants::LIST_LOC_USER_SIZE, $userModel->count($query)));
        }else{
            //render brother loc_list
            $parent = array_pop($location['pt']); 
            $location['pt'][]=$parent;
            $brothers = $locationModel->fetch(
                MongoQueryBuilder::newQuery()->query(array('$and'=>array(array('pt' => $parent),array('ptc'=>count($location['pt'])))))->sort(array('c.d'=>-1))->limit(20)->build()
            );
            $this->dataFlow->mergeOne('locations',$location);
            $this->assign(array('brother_loc_list'=>array_keys($brothers)));
            $this->dataFlow->mergeLocations($brothers);

            // render lepei_list
            $userModel=UserModel::getInstance();
            $type = intval($this->getRequest()->getRequest('type', ''));
            $query = array('lpt' => $lid );
            if( !empty($type) ){
                $query['l_t'] = $type;
            }
            $users=$userModel->fetch(
                MongoQueryBuilder::newQuery()
                    ->query($query)
                    ->limit(Constants::LIST_PAGE_SIZE)
                    ->skip(($page-1)* Constants::LIST_PAGE_SIZE)->build()
            );
            $this->dataFlow->mergeUsers($users);
            $this->assign(array('lepei_list'=>array_keys($users)));
            $this->assign($this->getPagination($page,
                Constants::LIST_PAGE_SIZE,
                UserModel::getInstance()->count($query)));
        }
        // assign like post status
        $like = LikeModel::getInstance()->fetchOne(
            array('uid'=>$this->userId ,
                'oid'=>$lid,
                'tp'=> Constants::LIKE_LOCATION
                ));
        $this->assign(array('likes'=> array_column( array( $like ) , null , 'oid' )));
        //
        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());
    }
}