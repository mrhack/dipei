<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangfeng
 * Date: 13-9-3
 * Time: 下午9:25
 * To change this template use File | Settings | File Templates.
 */
class IndexController extends Yaf_Controller_Abstract
{
    public function indexAction()
    {
        AppLocal::init("zh_CN");//load library well
        $model=new UserModel();
        var_dump($model->fetchOne());
        $this->getView()->assign(array('hello'=>'hello ,backend!'));
    }
}