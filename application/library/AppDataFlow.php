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
    public $flids=array();//get also parent locations
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
        $this->tids = array_diff(array_unique(array_map('intval', array_values($this->tids))), array_keys($this->translates));
        $this->uids = array_diff(array_unique(array_map('intval', array_values($this->uids))), array_keys($this->users));
        $this->fuids = array_unique(array_map('intval', array_values($this->fuids)));
        $this->lids = array_diff(array_unique(array_map('intval', array_values($this->lids))), array_keys($this->locations));
        $this->flids = array_unique(array_map('intval', array_values($this->flids)));

        $this->uids = array_diff($this->uids, $this->fuids);
        $this->lids = array_diff($this->lids, $this->flids);
    }

    public function mergeOne($dataSource,$data)
    {
        $datas=array($data['_id']=>$data);
        $func = 'merge' . ucfirst($dataSource);
        if(method_exists($this,$func)){
            $this->$func($datas);
        }else{
            $this->getLogger()->error('not found method '.$func);
        }
    }

    public function mergeUsers(&$users)
    {
        $userModel=UserModel::getInstance();
        foreach($users as $user){
            if(array_search($user['_id'],$this->fuids) !== false){
                $this->flids[] = $user['lid'];
            }else{
                $this->lids[] = $user['lid'];
                if(isset($user['ctr'])){
                    $this->lids[] = $user['ctr'];
                }
            }
            if(isset($user['cts'])){
                $this->tids = array_merge($this->tids, array_keys($user['cts']));
            }
            if(isset($user['ps'])){
                $rateModel=RateModel::getInstance();
                foreach($user['ps'] as &$project){
                    $project['p'] = $rateModel->convertRate($project['p'], AppLocal::currentMoney(),$project['pu']);
                    $project['pu']=AppLocal::currentMoney();
                    foreach($project['ds'] as $day){
                        foreach($day['ls'] as $line){
                            $this->tids[]=$line+1000;
                        }
                    }
                }
                unset($project);
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
            $this->users[$user['_id']] = $userModel->format($user);
        }
    }

    public function mergeLocations(&$locations)
    {
        $locationModel=LocationModel::getInstance();
        $parentLids=array();
        foreach($locations as $location){
            if(array_search($location['_id'],$this->flids) !== false){
                $parentLids = array_merge($parentLids, $location['pt']);
            }
            $this->tids[]=$location['_id']+1000;
            if(isset($location['tm_c'])){
                $this->tids = array_merge($this->tids, array_keys($location['tm_c']));
            }
            $this->locations[$location['_id']] = $locationModel->format($location);
        }
        $parentLids = array_diff($parentLids, $this->lids);
        $parentLocations = $locationModel->fetch(array('_id' => array('$in' => $parentLids)));
        foreach($parentLocations as $location){
            $this->lids[] = $location['_id'];
            $this->tids[]=$location['_id']+1000;
            if(isset($location['tm_c'])){
                $this->tids = array_merge($this->tids, array_keys($location['tm_c']));
            }
            $this->locations[$location['_id']] = $locationModel->format($location);
        }
    }

    public function mergeTranslates(&$translates)
    {
        $translateModel=TranslationModel::getInstance();
        foreach($translates as $translate){
            $this->translates[$translate['_id']] = $translateModel->translateWord($translate);
        }
    }


    public function flow()
    {
        $this->ensureInputs();
        if(!empty($this->uids) || !empty($this->fuids)){
            $userModel=UserModel::getInstance();
            $users = $userModel->fetch(array('_id' => array('$in' => $this->uids)),array('ps'=>false));
            if(!empty($this->fuids)){
                $users = array_merge($users,$userModel->fetch(array('_id'=>array('$in'=>$this->fuids))));
            }
            $this->mergeUsers($users);
        }
        $this->ensureInputs();
        if(!empty($this->lids) || !empty($this->flids)){
            $locationModel=LocationModel::getInstance();
            $allLids = array_merge($this->lids, $this->flids);
            $locations = $locationModel->fetch(array('_id'=>array('$in'=>$allLids)));
            $this->mergeLocations($locations);
        }
        $this->ensureInputs();
        if(!empty($this->tids)){
            $translateModel=TranslationModel::getInstance();
            $translates = $translateModel->fetch(array('_id'=>array('$in'=>$this->tids)));
            $this->mergeTranslates($translates);
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