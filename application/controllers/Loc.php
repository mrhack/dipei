<?php
/**
 * User: wangfeng
 * Date: 13-7-6
 * Time: 下午4:08
 */
class LocController extends BaseController
{
    public function validateAuth()
    {
        return true;
    }

    public function countryAction($lid)
    {
        $lid = intval($lid);
        if(!LocationModel::getInstance()->isValidId($lid)){
            $this->handleInvalidateAuth();
            return false;
        }
        //cities
        $locationModel=LocationModel::getInstance();
        $cityQuery=new MongoQueryBuilder();
        $cityQuery->query(array('pt.1' => $lid))->sort(array('c.p' => -1))->limit(5);
        $cities = $locationModel->formats($locationModel->fetch($cityQuery->build()));
        $this->assign(array('loc_list' => array_keys($cities)));
        $this->dataFlow->merge('locations', $cities);

        //new lepei
        $userModel=UserModel::getInstance();
        $users=$userModel->formats($userModel->fetch(array('lpt'=>$lid,'$limit'=>5)));
        $this->assign(array('lepei_list'=>array_keys($users)));
        $this->dataFlow->merge('users',$users);

        //render child loc_list
        $childs=$locationModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('pt'=>$lid))->sort(array('c.d'=>-1))->limit(20)->build()
        );
        $this->assign(array('child_loc_list' => array_keys($childs)));
        $this->dataFlow->merge('locations', $locationModel->formats($childs));

        $this->dataFlow->lids[]=$lid;
        $this->assign(array('LID'=>$lid));
        $this->assign($this->dataFlow->flow());

    }

    public function cityAction($lid)
    {
        $lid = intval($lid);
        if(!LocationModel::getInstance()->isValidId($lid)){
            $this->handleInvalidateAuth();
            return false;
        }
        $type = intval($this->getRequest()->getRequest('type', Constants::LEPEI_PROFESSIONAL));
        //render brother loc_list
        $locationModel=LocationModel::getInstance();
        $location = $locationModel->fetchOne(array('_id' => $lid));
        $brothers = $locationModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('pt' => array_pop($temp = $location['pt'])))->sort(array('c.d'=>-1))->limit(20)->build()
        );
        $this->dataFlow->merge('locations', array($lid => $locationModel->format($location)));
        $this->assign(array('brother_loc_list'=>array_keys($brothers)));
        $this->dataFlow->merge('locations', $locationModel->formats($brothers));
        //render child loc_list
        $childs=$locationModel->fetch(
            MongoQueryBuilder::newQuery()->query(array('pt'=>$lid))->sort(array('c.d'=>-1))->limit(20)->build()
        );
        $this->assign(array('child_loc_list' => array_keys($childs)));
        $this->dataFlow->merge('locations', $locationModel->formats($childs));

        //viewed lepei
        $this->assignViewedLepei();
        //new lepei
        $userModel=UserModel::getInstance();
        $query = MongoQueryBuilder::newQuery()->query(array('lpt' => $lid,'l_t'=>$type))->limit(5)->build();
        $users=$userModel->formats($userModel->fetch($query));
        $this->assign(array('lepei_list'=>array_keys($users)));
        $this->dataFlow->merge('users',$users);
        //
        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());
    }
}