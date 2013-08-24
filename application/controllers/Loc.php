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

        //feed
        $type=intval($this->getRequest()->getRequest('type',0));
        if(in_array($type,Constants::$FEED_TYPES)){
            $query = MongoQueryBuilder::newQuery()->query(array('lpt'=>$lid,'tp'=>$type))->sort(array('r_t' => -1))->limit(Constants::LIST_FEED_SIZE);
        }else{
            $query = MongoQueryBuilder::newQuery()->query(array('lpt'=>$lid))->sort(array('r_t' => -1))->limit(Constants::LIST_FEED_SIZE);
        }
        $feeds=FeedModel::getInstance()->fetch($query->build());
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

        //my fav lids
        if($this->userId){
            $myLikeLocations=$likeModel->fetch(
               MongoQueryBuilder::newQuery()->query(array('tp'=>Constants::LIKE_LOCATION,'uid'=>$this->userId))->sort(array('t'=>-1))->build()
            );
            $likeLocIds=array();
            foreach($myLikeLocations as $likeLocation){
                $this->dataFlow->lids[] = $likeLocation['oid'];
                $likeLocIds[] = $likeLocation['oid'];
            }
            $this->assign(array(
                'my_like_locations'=>$likeLocIds
            ));
        }

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
    }

    public function indexAction($lid)
    {
        // for history lepei
        $this->assignViewedLepei();
        $lid = intval($lid);
        $locationModel=LocationModel::getInstance();
        //
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
            $type = intval($this->getRequest()->getRequest('type', ''));
            $query = array('lpt' => $lid );
            if( !empty($type) ){
                $query['l_t'] = $type;
            }
            $users=$userModel->fetch(
                MongoQueryBuilder::newQuery()->query($query)->limit(5)->build()
            );
            $this->assign(array('lepei_list'=>array_keys($users)));
            $this->dataFlow->mergeUsers($users);
        }else{
            //render brother loc_list
            $parent = array_pop($location['pt']); $location['pt'][]=$parent;
            $brothers = $locationModel->fetch(
                MongoQueryBuilder::newQuery()->query(array('$and'=>array(array('pt' => $parent),array('ptc'=>count($location['pt'])))))->sort(array('c.d'=>-1))->limit(20)->build()
            );
            $this->dataFlow->mergeOne('locations',$location);
            $this->assign(array('brother_loc_list'=>array_keys($brothers)));
            $this->dataFlow->mergeLocations($brothers);
        }
        //
        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());
    }
}