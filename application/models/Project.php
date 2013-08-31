<?php
/**
 * User: wangfeng
 * Date: 13-7-27
 * Time: 下午1:20
 * @method static ProjectModel getInstance();
 */
class ProjectModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '_id' => new Schema('id', Constants::SCHEMA_INT),
            'uid' => new Schema('uid', Constants::SCHEMA_INT),
            't' => new Schema('title', Constants::SCHEMA_STRING, array(
                AppValidators::newLength(array('$le' => 100), _e('标题不得超过100字')),
            )),
            's' => new Schema('status', Constants::SCHEMA_INT, AppValidators::newStatusValidators()),
            'n' => new Schema('notice', Constants::SCHEMA_STRING),
            'p' => new Schema('price', Constants::SCHEMA_DOUBLE),
            'pu' => new Schema('price_unit', Constants::SCHEMA_INT), //tid
            'bp' => new Schema('base_price', Constants::SCHEMA_INT),
            'vc'=>new Schema('view_count',Constants::SCHEMA_INT),
            'lk' => new Schema('like', Constants::SCHEMA_INT),
            'c_t'=> new Schema('create_time',Constants::SCHEMA_DATE),
            'r_c'=>new Schema('reply_count',Constants::SCHEMA_INT),
            'r_t'=>new Schema('reply_time',Constants::SCHEMA_DATE),
            'tm' => array(
                new Schema('travel_themes', Constants::SCHEMA_ARRAY),
                '$value' => new Schema('theme', Constants::SCHEMA_INT) //tid
            ),
            'ts' => array(
                new Schema('travel_services', Constants::SCHEMA_ARRAY),
                '$value' => new Schema('service', Constants::SCHEMA_INT) //tid
            ),
            'ds' => array(
                new Schema('days', Constants::SCHEMA_ARRAY),
                'ls' => array(
                    new Schema('lines', Constants::SCHEMA_ARRAY), //
                    '$value' => new Schema('line', Constants::SCHEMA_INT) //lid
                ),
                'dsc' => new Schema('desc'),
            ),
        );
    }

    public function passProject($projectInfo)
    {
        if($projectInfo['s'] !== Constants::STATUS_PASSED){
            $projectInfo['s']=Constants::STATUS_PASSED;
            $this->updateProject($projectInfo);
        }
    }

    public function updateProject($projectInfo)
    {
        $this->saveProject($projectInfo);
    }

    public function saveProject($projectInfo)
    {
        $updateLocations=array();
        $beforeProject = $this->fetchOne(array('_id' => $projectInfo['_id']));

        $this->save($projectInfo);
        //save feed
        $user = UserModel::getInstance()->fetchOne(array('_id' => $projectInfo['uid']));
        FeedModel::getInstance()->saveFeed($projectInfo['_id'], Constants::FEED_TYPE_PROJECT ,$projectInfo['uid'], $user['lid'],  $projectInfo['s']);
        //ensure location count
        $this->buildLocationUpdateCount($updateLocations, $beforeProject, -1);
        $this->buildLocationUpdateCount($updateLocations, $projectInfo, 1);
        $locationModel=LocationModel::getInstance();
        foreach($updateLocations as $lid=>$updateLocation){
            if($this->_isEmptyUpdateLocation($updateLocation)){
                continue;
            }
            $locationModel->update(
                $updateLocations[$lid],
                array('_id'=>$lid)
            );
        }
        //ensure user index
        $updateUser=array();
        if($projectInfo['s']>=0){
            $updateUser['$addToSet']=array('ps'=>$projectInfo['_id']);
        }else{
            $updateUser['$pull']=array('ps'=>$projectInfo['_id']);
        }
        //before unpass,after pass
        if($projectInfo['s']>=Constants::STATUS_PASSED &&
                (!isset($beforeProject['s']) || $beforeProject['s']<Constants::STATUS_PASSED)){
            $updateUser['$inc'] = array('pc'=>1);
        }//before pass,after unpass
        else if($projectInfo['s']<Constants::STATUS_PASSED &&
                $beforeProject['s']>=Constants::STATUS_PASSED){
            $updateUser['$inc'] = array('pc' => -1);
        }
        UserModel::getInstance()->update($updateUser,array('_id'=>$projectInfo['uid']));
    }

    public function update($data,$find=null,$options=array()){
        return parent::update($data, $find, $options);
    }

    public function removeProject($projectInfo)
    {
        $projectInfo['s']=Constants::STATUS_DELETE;
        $this->updateProject($projectInfo);
    }

    public function addProject($projectInfo)
    {
        if(!isset($projectInfo['uid']) || !isset($projectInfo['t']) || !isset($projectInfo['s']) || !isset($projectInfo['ds'])){
            throw new AppException(Constants::CODE_INVALID_MODEL);
        }
        if(!isset($projectInfo['_id'])){
            $projectInfo['_id'] = $this->getNextId();
        }
        $this->saveProject($projectInfo);
        return $projectInfo['_id'];
    }

    private function _isEmptyUpdateLocation($updateLocation)
    {
        $empty=true;
        array_walk_recursive($updateLocation,function($v) use(&$empty){
            if($v != 0){
                $empty=false;
            }
        });
        return $empty;
    }

    private function buildLocationUpdateCount(&$updateLocations, &$project, $align)
    {
        $locationModel = LocationModel::getInstance();
        if (!isset($project['ds'])) {
            return;
        }
        $lids = array();
        foreach ($project['ds'] as $day) {
            if (!isset($day['ls']) || !isset($project['tm'])) {
                continue;
            }
            foreach ($day['ls'] as $lid) {
                $lids[] = $lid;
            }
        }
        $locations = $locationModel->fetch(array('_id' => array('$in' => $lids)), array('pt' => true));
        foreach ($locations as $location) {
            $lids = array_merge($lids, $location['pt']);
        }
        foreach (array_unique($lids) as $lid) {
            if ($project['s'] === Constants::STATUS_PASSED) {
                foreach ($project['tm'] as $tid) {
                    $updateLocations[$lid]['$inc']['tm_c' . '.' . $tid] += 1 * $align;
                }
                $updateLocations[$lid]['$inc']['c.p'] += 1 * $align;
            }
        }
    }
}