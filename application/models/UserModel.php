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
 */
class UserModel extends BaseModel
{

    public function getFormatSchema()
    {
        return array(
            'n' => 'name',
            's' => 'sex',
            'em' => 'email',
            'pw' => 'password',
            'h' => 'head',
            'dsc' => 'desc',
            'c_t' => 'create_time',
            'ims' => 'images',
            'lk' => 'like',
            'm' => 'money',
            'l' => 'lang',
            'ls' => 'license',
            'con' => 'contact',
            'l_t' => 'lepei_type',
            'p' => array(
                'project',//self name
                't' => 'title',
                'tm' => 'travel_schemas',
                'ts' => 'travel_services',
                'ds' => array(
                    'days',
                    'dsc' => 'desc',
                    'lk' => 'like'
                )),
        );
    }

    public function createUser($userInfo)
    {

    }

}