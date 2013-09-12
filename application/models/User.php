<?php
/**
 * User: wangfeng
 * Date: 13-6-1
 * Time: 下午12:22
 */

/**
 *
 * @method static UserModel getInstance()
 */
class UserModel extends BaseModel
{
    use Strategy_Singleton;

    public function __construct()
    {
        //ensure index
        $this->ensureIndex(array('n'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
        $this->ensureIndex(array('em'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
    }

    public function getSchema()
    {
        return
            //common
            array(
                '_id'=>new Schema('id',Constants::SCHEMA_INT),
                'n' => new Schema('name',Constants::SCHEMA_STRING,array(
                        new Validator_NickEmail(_e('昵称不能重复'),array($this,'getUniqueEscape')),
                        AppValidators::newLength(array('$gt'=>0,'$lt'=>50),_e('名称应在1~50字范围内'))
                    )
                ),
                's'=> new Schema('status',Constants::SCHEMA_INT), //user status
                'sx' => new Schema('sex',Constants::SCHEMA_INT), //
                'b'=>array(
                    new Schema('birth',Constants::SCHEMA_OBJECT),
                    'y'=>new Schema('year',Constants::SCHEMA_INT),
                    'm'=>new Schema('month',Constants::SCHEMA_INT),
                    'd'=>new Schema('day',Constants::SCHEMA_INT)
                ),
                'lid'=>new Schema('lid',Constants::SCHEMA_INT),//host lid
                'ctr'=>new Schema('country',Constants::SCHEMA_INT),//country lid
                'em' => new Schema('email',Constants::SCHEMA_STRING,array(
                        new Validator_NickEmail(_e('邮箱不能重复'),array($this,'getUniqueEscape')),
                        AppValidators::newRequired(_e('邮箱不能为空'))
                    )
                ),
                'pw' => new Schema('password',Constants::SCHEMA_STRING),
                'h' => new Schema('head',Constants::SCHEMA_STRING),
                'c_t' => new Schema('create_time',Constants::SCHEMA_DATE),
                'o_t' => new Schema('online_time',Constants::SCHEMA_DATE),
                // 勋章
                'bgs' => array(
                    new Schema('badges',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('bgstid',Constants::SCHEMA_INT)
                ),
                'l_vts'=>array(
                    new Schema('loc_view_times',Constants::SCHEMA_OBJECT),
                    '$key'=>new Schema('lid',Constants::SCHEMA_INT),
                    '$value'=>new Schema('time',Constants::SCHEMA_DATE)
                ),
                'msgs'=>array(
                    new Schema('messages',Constants::SCHEMA_OBJECT),
                    'r'=>new Schema('reply',Constants::SCHEMA_INT),
                    'm'=>new Schema('message',Constants::SCHEMA_INT),
                    's'=>new Schema('sysMessage',Constants::SCHEMA_INT)
                ),
            )
            //lepei
            +array(
                'as'=>new Schema('auth_status',Constants::SCHEMA_INT),
                'dsc' => new Schema('desc',Constants::SCHEMA_STRING),
                'l_t' => new Schema('lepei_type',Constants::SCHEMA_INT,array(
                    AppValidators::newRange(Constants::$LEPEI_TYPES,_e('非法的乐陪类型'))
                )),
                'ims' =>array(
                    new Schema('images',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('url',Constants::SCHEMA_STRING)
                ),
                'lk' => new Schema('like',Constants::SCHEMA_INT),
                'ls' => array(
                    new Schema('langs',Constants::SCHEMA_ARRAY),
                    '$key'=>new Schema('lang',Constants::SCHEMA_INT),//tid
                    '$value'=>new Schema('familiar',Constants::SCHEMA_INT)//tid
                ),
                'vc'=>new Schema('view_count',Constants::SCHEMA_INT),
                //dipei
                'lcs' => new Schema('license',Constants::SCHEMA_STRING),
                'cts' => array(
                    new Schema('contacts',Constants::SCHEMA_OBJECT),
                    '$key'=>new Schema('contact',Constants::SCHEMA_INT),//tid
                    '$value'=>new Schema('value',Constants::SCHEMA_STRING)
                ),
                //projects
                'ps'=>array(
                    new Schema('projects',Constants::SCHEMA_ARRAY,array(
                        AppValidators::newCount(array('$lte'=>5),_e('项目数最大5个'))
                    )),
                    '$value'=>new Schema('project_id',Constants::SCHEMA_INT)
                )
            )
            + array(
                'lpt'=>new Schema('location_path',Constants::SCHEMA_ARRAY),//索引字段，为了显示新增乐陪
                'pc'=>new Schema('pass_count',Constants::SCHEMA_INT),//通过的项目数
                'ils'=>array(
                    new Schema('index_langs',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('lang',Constants::SCHEMA_INT)
                ),
            )
        ;
    }

    public function incCount($id,$key,$amount=1)
    {
        return $this->update(array('$inc' => array($key => $amount)), array('_id' => $id));
    }

    public function clearCount($id,$key)
    {
        return $this->update(array('_id'=>$id,$key=>0));
    }

    public function createUser($userInfo)
    {
        $user=array(
            'n'=>$userInfo['n'],
            'em'=>$userInfo['em'],
            'pc'=>0,
            'pw'=>md5($userInfo['pw']),
            'c_t'=>new MongoDate(time()),

        );
        $ret=$this->insert($user);
        $this->login($userInfo);
        return $ret['inserted'];
    }

    public function isLepei($userInfo)
    {
        return !empty($userInfo) && isset($userInfo['l_t']) && array_search($userInfo['l_t'], Constants::$LEPEI_TYPES)!==false;
    }

    private function buildLocationUpdateCount(&$updateLocations,&$userInfo,$align){
        //update location dipei count with lid
        if(isset($userInfo['lid'])){
            $locationModel=LocationModel::getInstance();
            $updateLocations[$userInfo['lid']]['$inc']['c.d'] +=1*$align;
            $location=$locationModel->fetchOne(array('_id' => $userInfo['lid']),array('pt'=>true));
            if(!empty($location)){
                foreach($location['pt'] as $lid){
                    $updateLocations[$lid]['$inc']['c.d']+=1 * $align;
                }
            }
        }
    }

    private function ensureIndexFields(&$userInfo)
    {
        if(isset($userInfo['lid']) && $userInfo['lid']>0){
            $location=LocationModel::getInstance()->fetchOne(array('_id'=>$userInfo['lid']));
            $userInfo['lpt']=$location['pt'];
            $userInfo['lpt'][] = $userInfo['lid'];
        }
        if(isset($userInfo['ls'])){
            $userInfo['ils'] = array_map('intval',array_keys($userInfo['ls']));
        }
        if(isset($userInfo['ps'])){
            foreach($userInfo['ps'] as &$project){
                if(!isset($project['_id'])){//new project
                    $project['_id']=$this->getNextId('project');
                }
                if(isset($project['p'])){
                    $project['bp'] = intval(RateModel::getInstance()->convertRate($project['p'], $project['pu'])*1000000);
                }
            }
            unset($project);
        }
    }

    public function updateUser($userInfo){
        $beforeUser = $this->fetchOne(array('_id' => $userInfo['_id']));
        $this->ensureIndexFields($userInfo);
        $this->update($userInfo);

        //ensure location count
        if($this->isLepei($beforeUser) || $this->isLepei($userInfo) ){
            $updateLocations=array();
            $this->buildLocationUpdateCount($updateLocations,$beforeUser,-1);
            $this->buildLocationUpdateCount($updateLocations,$userInfo,1);
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
        }
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

    /**
     * 根据email和密码进行登陆。若成功则返回该user信息，否则返回null
     * @param $userInfo
     * @return mixed
     */
    public function login($userInfo)
    {
        $dbUser=$this->fetchOne(array('$or'=>array(array('em'=>$userInfo['em']),array('n'=>$userInfo['n'])),'pw'=>md5($userInfo['pw'])));
        if(!empty($dbUser)){
            $this->setLogin($dbUser);
            $this->getLogger()->info('login success',$userInfo);
        }
        return $dbUser;
    }

    public function passLepei($userInfo)
    {
        $userInfo['as']=Constants::STATUS_PASSED;
        $this->updateUser($userInfo);
    }

    public function setLogin($dbUser)
    {
        if(!empty($dbUser)){
            $session=Yaf_Session::getInstance();
            $session->start();
            $session['user'] = $dbUser;
        }
    }

    public function getLoginUid(){
        if(Yaf_Session::getInstance()->has('user')){
            return Yaf_Session::getInstance()['user']['_id'];
        }else{
            return null;
        }
    }


    public function getUniqueEscape($data)
    {
        if(Yaf_Session::getInstance()->has('user') && !empty($data)){
            return $data['_id'] === Yaf_Session::getInstance()['user']['_id'];
        }
    }
}