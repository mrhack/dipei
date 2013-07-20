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
        $this->getCollection()->ensureIndex(array('n'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
        $this->getCollection()->ensureIndex(array('em'=>1),array('background'=>true,'unique'=>true,'dropDups'=>true));
    }

    public function getSchema()
    {
        return
            //common
            array(
                '_id'=>new Schema('id',Constants::SCHEMA_INT),
                'n' => new Schema('name',Constants::SCHEMA_STRING,array(
                        AppValidators::newUnique($this,_e('名称不能重复'),$this->getUniqueEscape()),
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
                'em' => new Schema('email',Constants::SCHEMA_STRING,array(
                        AppValidators::newUnique($this,_e('邮箱不能重复'),$this->getUniqueEscape()),
                    )
                ),
                'pw' => new Schema('password',Constants::SCHEMA_STRING),
                'h' => new Schema('head',Constants::SCHEMA_STRING),
                'c_t' => new Schema('create_time',Constants::SCHEMA_DATE),
                // 勋章
                'bgs' => array(
                    new Schema('badges',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('bgstid',Constants::SCHEMA_INT)
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
            )
            +array(
                'ps' => array(
                    new Schema('projects',Constants::SCHEMA_ARRAY),//self name
                    '_id'=>new Schema('id',Constants::SCHEMA_INT),
                    't' => new Schema('title',Constants::SCHEMA_STRING,array(
                        AppValidators::newLength(array('$le'=>20),_e('标题不得超过20字')),
                    )),
                    'n' => new Schema('notice',Constants::SCHEMA_STRING ),
                    'p' => new Schema('price',Constants::SCHEMA_INT ),
                    'pu' => new Schema('price_unit' , Constants::SCHEMA_INT ),//tid
                    'bp'=>new Schema('base_price',Constants::SCHEMA_INT),
                    'lk' => new Schema('like',Constants::SCHEMA_INT),
                    'tm' => array(
                        new Schema('travel_themes',Constants::SCHEMA_ARRAY),
                        '$value'=>new Schema('theme',Constants::SCHEMA_INT)//tid
                    ),
                    'ts' => array(
                        new Schema('travel_services',Constants::SCHEMA_ARRAY),
                        '$value'=>new Schema('service',Constants::SCHEMA_INT)//tid
                    ),
                    'ds' => array(
                        new Schema('days',Constants::SCHEMA_ARRAY),
                        'ls'=>array(
                            new Schema('lines',Constants::SCHEMA_ARRAY),//
                            '$value'=>new Schema('line',Constants::SCHEMA_INT)//lid
                        ),
                        'dsc' => new Schema('desc'),
                    ),
                ),
            )//indexes
            + array(
                'lpt'=>new Schema('lpt',Constants::SCHEMA_ARRAY),//索引字段，为了显示新增乐陪
                'pc'=>new Schema('project_count',Constants::SCHEMA_INT),//通过的项目数
                'ils'=>array(
                    new Schema('index_langs',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('lang',Constants::SCHEMA_INT)
                ),
            )
        ;
    }

    public function createUser($userInfo)
    {
        $user=array(
            'n'=>$userInfo['n'],
            'em'=>$userInfo['em'],
            'pw'=>md5($userInfo['pw']),
            'c_t'=>new MongoDate(time()),

        );
        $ret=$this->insert($user);
        $this->login($userInfo);
        return $ret['inserted'];
    }

    /**
     * 根据id将临时地陪数据更新过来
     * @param $userInfo
     */
    public function grantLepei($userId)
    {
        $tempUser=LepeiTempModel::getInstance()->fetchOne(array('_id' => $userId));
        if(!empty($tempUser)){
            $this->updateUser($tempUser);
        }
    }

    public function isLepei($userInfo)
    {
        return !empty($userInfo) && isset($userInfo['l_t']) && array_search($userInfo['l_t'], Constants::$LEPEI_TYPES)!==false;
    }

    public function findProjectFromUser($userInfo,$pid)
    {
        if(!empty($userInfo) && !empty($userInfo['ps'])){
            foreach($userInfo['ps'] as &$project){
                if($project['_id'] == $pid){
                    return $project;
                }
            }
        }
        return null;
    }

    public function removeProject($userInfo,$pid)
    {
        $pid = intval($pid);
        $project = $this->findProjectFromUser($userInfo,$pid);
        if(empty($project)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        foreach($userInfo['ps'] as $k=>$project){
            if($pid == $project['_id']){
                unset($userInfo['ps'][$k]);
                break;
            }
        }
        $this->updateUser($userInfo);
    }

    public function updateProject($userInfo,$project){
        $pid = $project['_id'];
        if(!!$this->findProjectFromUser($userInfo,$pid)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        foreach($userInfo['ps'] as $k=>$p){
            if($p['_id'] == $pid){
                $userInfo['ps'][$k]=$project;
                break;
            }
        }
        $this->updateUser($userInfo);
    }

    /**
     * @param $userInfo
     * @param $projct
     */
    public function addProject($userInfo,$projct)
    {
        $userModel=UserModel::getInstance();
        if(empty($projct)){
            throw new AppException(Constants::CODE_NOT_FOUND_PROJECT);
        }
        $userInfo['ps'][]=$projct;
        $userModel->updateUser($userInfo);
    }

    private function buildLocationUpdateCount(&$updateLocations,&$userInfo,$align){
        $locationModel=LocationModel::getInstance();
        //update location dipei and project count with lid
        if(isset($userInfo['lid'])){
            $updateLocations[$userInfo['lid']]['$inc']['c.d'] +=1*$align;
            $location=$locationModel->fetchOne(array('_id' => $userInfo['lid']),array('pt'=>true));
            if(!empty($location)){
                foreach($location['pt'] as $lid){
                    $updateLocations[$lid]['$inc']['c.d']+=1 * $align;
                }
            }
        }
        //update location theme count with project themes and line lids
        if(isset($userInfo['ps'])){
            foreach($userInfo['ps'] as $project) {
                if(!isset($project['ds'])){
                    continue;
                }
                $lids=array();
                foreach($project['ds'] as $day){
                    if(!isset($day['ls']) || !isset($project['tm'])){
                        continue;
                    }
                    foreach($day['ls'] as $lid){
                        $lids[]=$lid;
                    }
                }
                $locations=$locationModel->fetch(array('_id' => array('$in' => $lids)),array('pt'=>true));
                foreach($locations as $location){
                    $lids = array_merge($lids, $location['pt']);
                }
                foreach(array_unique($lids) as $lid){
                    foreach($project['tm'] as $tid){
                        $updateLocations[$lid]['$inc']['tm_c'.'.'.$tid]+=1 * $align;
                    }
                    $updateLocations[$lid]['$inc']['c.p'] += 1 *$align;
                }
            }
        }
    }

    public function update($data,$find=null,$options=array()){
        if(isset($userInfo['lid']) && $userInfo['lid']>0){
            $location=LocationModel::getInstance()->fetchOne(array('_id'=>$userInfo['lid']));
            $userInfo['lpt']=$location['pt'];
            $userInfo['lpt'][] = $userInfo['lid'];
        }
        if(isset($userInfo['ls'])){
            $userInfo['ils'] = array_map('intval',array_keys($userInfo['ls']));
        }
        return parent::update($data, $find, $options);
    }

    public function updateUser($userInfo){
       if(!isset($userInfo['_id'])){
           return false;
       }
       if(isset($userInfo['ps'])){
           foreach($userInfo['ps'] as &$project){
               if(!isset($project['_id'])){//new project
                   $project['_id']=$this->getNextId('project');
               }
           }
           unset($project);
       }
       $beforeUser = $this->fetchOne(array('_id' => $userInfo['_id']));
       $this->update($userInfo);

        if( isset($userInfo['ps']) && ($this->isLepei($beforeUser) || $this->isLepei($userInfo)) ){
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
        $dbUser=$this->fetchOne(array('em'=>$userInfo['em'],'pw'=>md5($userInfo['pw'])));
        if(!empty($dbUser)){
            $this->setLogin($dbUser);
            $this->getLogger()->info('login success',$userInfo);
        }
        return $dbUser;
    }

    public function setLogin($dbUser)
    {
        if(!empty($dbUser)){
            $session=Yaf_Session::getInstance();
            $session->start();
            $session['user'] = $dbUser;
        }
    }

    public function getUniqueEscape()
    {
        return function($data){
            if(Yaf_Session::getInstance()->has('user') && !empty($data)){
                return $data['_id'] === Yaf_Session::getInstance()['user']['_id'];
            }
        };
    }
}