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

        $this->dataFlow->lids[]=$lid;
        $this->assign(array('LID'=>$lid));
        $this->assign($this->dataFlow->flow());

    }

    public function cityAction($lid)
    {
        $lid = intval($lid);

        //viewed lepei
        $this->assignViewedLepei();
        //new lepei
        $userModel=UserModel::getInstance();
        $users=$userModel->formats($userModel->fetch(array('lpt'=>$lid,'$limit'=>5)));
        $this->assign(array('lepei_list'=>array_keys($users)));
        $this->dataFlow->merge('users',$users);
        //
        $this->dataFlow->lids[]=$lid;
        $this->assign(array('LID' => $lid));
        $this->assign($this->dataFlow->flow());
    }
}