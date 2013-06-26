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

    public function getSchema()
    {
        return array(
            //common
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            'n' => new Schema('name',Constants::SCHEMA_STRING),
            // acount status
            's'=> new Schema('status',Constants::SCHEMA_INT),
            // lepei auth status
            'as'=>new Schema('auth_status',Constants::SCHEMA_INT),
            'sx' => new Schema('sex',Constants::SCHEMA_INT),
            'ag' => new Schema('age',Constants::SCHEMA_INT),
            'em' => new Schema('email',Constants::SCHEMA_STRING),
            'pw' => new Schema('password',Constants::SCHEMA_STRING),
            'h' => new Schema('head',Constants::SCHEMA_STRING),
            'dsc' => new Schema('desc',Constants::SCHEMA_STRING),
            'c_t' => new Schema('create_time',Constants::SCHEMA_INT),
            'ims' =>array(
                new Schema('images',Constants::SCHEMA_ARRAY),
                '$value'=>new Schema('url',Constants::SCHEMA_STRING)
             ),
            // 勋章
            'bgs' => array(
                new Schema('badges',Constants::SCHEMA_ARRAY),
                '$value'=>new Schema('bgstid',Constants::SCHEMA_INT)
                ),
            'lk' => new Schema('like',Constants::SCHEMA_INT),
            'm' => new Schema('money',Constants::SCHEMA_STRING),//CNY
            'ls' => array(
                new Schema('langs',Constants::SCHEMA_ARRAY),
                '$key'=>new Schema('lang',Constants::SCHEMA_INT),//tid
                '$value'=>new Schema('familiar',Constants::SCHEMA_INT)//tid
            ),
            'lid'=>new Schema('lid',Constants::SCHEMA_INT),//host lid
            'vc'=>new Schema('view_count',Constants::SCHEMA_INT),
            //dipei
            'lcs' => new Schema('license',Constants::SCHEMA_STRING),
            'cts' => array(
                new Schema('contacts',Constants::SCHEMA_OBJECT),
                '$key'=>new Schema('contact',Constants::SCHEMA_INT),//tid
                '$value'=>new Schema('value',Constants::SCHEMA_STRING)
             ),
            'l_t' => new Schema('lepei_type'),
            'ps' => array(
                new Schema('projects',Constants::SCHEMA_ARRAY),//self name
                'id'=>new Schema('id',Constants::SCHEMA_INT),
                't' => new Schema('title'),
                'n' => new Schema('notice' , Constants::SCHEMA_STRING ),
                'p' => new Schema('price' , Constants::SCHEMA_INT ),
                'pu' => new Schema('price_unit' , Constants::SCHEMA_INT ),//tid
                'cm' => array(
                    new Schema('custom_themes',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('theme',Constants::SCHEMA_STRING)
                ),
                'tm' => array(
                    new Schema('travel_themes',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('theme',Constants::SCHEMA_INT)//tid
                ),
                'cs' => array(
                    new Schema('custom_services',Constants::SCHEMA_ARRAY),
                    '$value'=>new Schema('service',Constants::SCHEMA_STRING)//tid
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
                'lk' => new Schema('like')
            ),
        );
    }

    public function createUser($userInfo)
    {
        $userInfo['pw']=md5($userInfo['pw']);
        $this->insert($userInfo);
        $this->getLogger()->info('new user success',$userInfo);
    }

    /**
     * 根据id将临时地陪数据更新过来
     * @param $userInfo
     */
    public function grantLepei($userInfo)
    {
        //sync lepei_temp
        //sync location count
    }

    public function getLoginUser()
    {
        if (Yaf_Session::getInstance()->has('user')) {
            return $this->fetchOne(array('_id'=>Yaf_Session::getInstance()['user']['_id']));
        }
        return null;
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
            $session=Yaf_Session::getInstance();
            $session->start();
            $session['user'] = $dbUser;
        }
        return $dbUser;
    }
}