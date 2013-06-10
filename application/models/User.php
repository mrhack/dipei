<?php
/**
 * User: wangfeng
 * Date: 13-6-1
 * Time: 下午12:22
 */

/**
 * schema:
 * {
 * n[name]:'wangfeng'
 * s[sex]:0
 * em[email]:'sddd@dff.com'
 * pw[password]:'dffjskxk' //hashed
 * ctr[country]:'China'
 * ct[city]:'Beijing'
 * h[head]:'http://xxx'
 * dsc[desc]:'ssss'
 * c_t[create_time]:13945854953
 * ims['images']:['http://xx_ow_oh_w_h.png',...]
 * lk[like]:0
 * m[oney]:[] //货币类型
 * l[ang]:[china:1]
 * ls[license]:'flcindsfas'
 * l_t[lepei_type]:0,
 * con[contact]:{tel:'xxx',qq:'xxx'},
 * p[project]:{
 *  t[title]:'xxxx',
 *  tms[travel themes]:[]
 *  tss[travel services]:[]
 *  ds[ays]:[
 *      {
 *       l[ine]:[scene1,scene2],
 *       dsc[desc]:'sdfasdfsdkjsakldf',//rich content,
 *       ims[images]:['http://xx_ow_oh_w_h.png']
 *       lk[like]:0
 *       },...
 * ]
 * }
 *
 * }
 *
 * @method static UserModel getInstance()
 */
class UserModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            'n' => new Schema('name',Constants::SCHEMA_STRING),
            's' => new Schema('sex',Constants::SCHEMA_INT),
            'st'=> new Schema('status',Constants::SCHEMA_INT),
            'em' => new Schema('email',Constants::SCHEMA_STRING),
            'pw' => new Schema('password',Constants::SCHEMA_STRING),
            'h' => new Schema('head',Constants::SCHEMA_STRING),
            'dsc' => new Schema('desc',Constants::SCHEMA_STRING),
            'c_t' => new Schema('create_time',Constants::SCHEMA_INT),
            'ims' =>new Schema('images',Constants::SCHEMA_ARRAY),
            'lk' => new Schema('like',Constants::SCHEMA_INT),
            'm' => new Schema('money',Constants::SCHEMA_STRING),
            'l' => new Schema('lang'),
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