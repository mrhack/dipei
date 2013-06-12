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
            'n' => new Schema('name',Constants::SCHEMA_STRING),
            's'=> new Schema('status',Constants::SCHEMA_INT),
            'sx' => new Schema('sex',Constants::SCHEMA_INT),
            'em' => new Schema('email',Constants::SCHEMA_STRING),
            'pw' => new Schema('password',Constants::SCHEMA_STRING),
            'h' => new Schema('head',Constants::SCHEMA_STRING),
            'dsc' => new Schema('desc',Constants::SCHEMA_STRING),
            'c_t' => new Schema('create_time',Constants::SCHEMA_INT),
            'ims' =>new Schema('images',Constants::SCHEMA_ARRAY),
            'lk' => new Schema('like',Constants::SCHEMA_INT),
            'm' => new Schema('money',Constants::SCHEMA_STRING),
            'l' => new Schema('lang'),
            'lid'=>new Schema('lid',Constants::SCHEMA_INT),
            //dipei
            'ls' => new Schema('license'),
            'con' => new Schema('contact'),
            'l_t' => new Schema('lepei_type'),
            'p' => array(
                new Schema('project'),//self name
                't' => new Schema('title'),
                'tm' => new Schema('travel_schemas'),
                'ts' => new Schema('travel_services'),
                'ds' => array(
                    new Schema('days'),
                    'dsc' => new Schema('desc'),
                    'lk' => new Schema('like')
                )),
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


    /**
     * 根据email和密码进行登陆。若成功则返回该user信息，否则返回null
     * @param $userInfo
     * @return mixed
     */
    public function login($userInfo)
    {
        $dbUser=$this->fetchOne(array('em'=>$userInfo['em'],'pw'=>md5($userInfo['pw'])));
        return $dbUser;
    }
}