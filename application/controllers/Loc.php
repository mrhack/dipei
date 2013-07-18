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
                return false;
            }
        }
        return true;
    }

    public function indexAction($lid)
    {
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
        }else{
            //render brother loc_list
            $parent = array_pop($location['pt']); $location['pt'][]=$parent;
            $brothers = $locationModel->fetch(
                MongoQueryBuilder::newQuery()->query(array('$and'=>array(array('pt' => $parent),array('pt'=>array('$size'=>count($location['pt']))))))->sort(array('c.d'=>-1))->limit(20)->build()
            );
            $this->dataFlow->mergeOne('locations',$location);
            $this->assign(array('brother_loc_list'=>array_keys($brothers)));
            $this->dataFlow->mergeLocations($brothers);
        }

        //render child loc_list
        $childs=$locationModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('pt'=>$lid))->sort(array('c.d'=>-1))->limit(20)->build()
        );
        $this->assign(array('child_loc_list' => array_keys($childs)));
        $this->dataFlow->mergeLocations($childs);

        //render new lepei
        $userModel=UserModel::getInstance();
        $type = intval($this->getRequest()->getRequest('type', Constants::LEPEI_PROFESSIONAL));
        $users=$userModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('lpt' => $lid,'l_t'=>$type))->limit(5)->build()
        );
        $this->assign(array('lepei_list'=>array_keys($users)));
        $this->dataFlow->mergeUsers($users);
        //
        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());
    }
}