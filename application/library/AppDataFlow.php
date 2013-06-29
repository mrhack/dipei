<?php
/**
 * User: wangfeng
 * Date: 13-6-14
 * Time: 下午11:49
 */
class AppDataFlow
{
    use AppComponent;
    use Strategy_Singleton;

    //------------in------------------
    public $tids=array();//translation ids
    public $lids=array();//location ids
    public $uids=array();//uids
    public $fuids=array();//full uids

    //-----------out------------------
    public $users=array();
    public $locations=array();
    public $translates=array();
    public $moneys=array();
    public $rates=array();
    public $results=array();

    public function ensureInputs()
    {
        $this->tids = array_unique(array_map('intval', array_values($this->tids)));
        $this->lids = array_unique(array_map('intval', array_values($this->lids)));
        $this->uids = array_unique(array_map('intval', array_values($this->uids)));
        $this->fuids = array_unique(array_map('intval', array_values($this->fuids)));
        $this->uids = array_diff($this->uids, $this->fuids);
    }

    public function &flow()
    {
        $this->ensureInputs();
        if(!empty($this->uids)){
            $userModel=UserModel::getInstance();
            $users = $userModel->fetch(array('_id' => array('$in' => $this->uids)),array('ps'=>false));
            if(!empty($this->fuids)){
                $users = array_merge($users,$userModel->fetch(array('_id'=>array('$in'=>$this->fuids))));
            }
            foreach($users as $user){
                $this->users[$user['_id']] = $userModel->format($user);
                $this->lids[] = $user['lid'];
                if(isset($user['cts'])){
                    $this->tids = array_merge($this->tids, array_keys($user['cts']));
                }
                if(isset($user['ps'])){
                   if(isset($user['ps']['tm'])) {
                       $this->tids = array_merge($this->tids, $user['ps']['tm']);
                   }
                   if(isset($user['ps']['ts'])) {
                       $this->tids = array_merge($this->tids, $user['ps']['ts']);
                   }
                }
                if(isset($user['ls'])){
                    $this->tids = array_merge($this->tids, array_keys($user['ls']));
                    $this->tids = array_merge($this->tids, array_values($user['ls']));
                }
            }
        }
        $this->ensureInputs();
        if(!empty($this->lids)){
            $locationModel=LocationModel::getInstance();
            $locations = $locationModel->fetch(array('_id'=>array('$in'=>$this->lids)));
            $parentLids=array();
            foreach($locations as $location){
                $parentLids = array_merge($parentLids, $location['pt']);
                $this->locations[$location['_id']] = $locationModel->format($location);
                $this->tids[]=$location['_id']+1000;
                if(isset($location['tm_c'])){
                    $this->tids = array_merge($this->tids, array_keys($location['tm_c']));
                }
            }
            $parentLids = array_diff($parentLids, $this->lids);
            $parentLocations = $locationModel->fetch(array('_id' => array('$in' => $parentLids)));
            foreach($parentLocations as $location){
                $this->lids[] = $location['_id'];
                $this->locations[$location['_id']] = $locationModel->format($location);
                $this->tids[]=$location['_id']+1000;
                if(isset($location['tm_c'])){
                    $this->tids = array_merge($this->tids, array_keys($location['tm_c']));
                }
            }
        }
        $this->ensureInputs();
        if(!empty($this->tids)){
            $translateModel=TranslationModel::getInstance();
            $translates = $translateModel->fetch(array('_id'=>array('$in'=>$this->tids)));
            foreach($translates as $translate){
                //FIXME local?
                $this->translates[$translate['_id']] = $translateModel->translateWord($translate);
            }
        }

        //replace location name
        if(!empty($this->locations)){
            foreach(array_keys($this->locations) as $k){
                $this->locations[$k]['name'] = $this->translates[$k + 1000];
            }
        }

        $this->results=array(
            'USERS'=>$this->users,
            'LOCATIONS'=>$this->locations,
            'TRANSLATES'=>$this->translates,
        );
        return $this->results;
    }
}