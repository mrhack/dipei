<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wangfeng
 * Date: 13-9-3
 * Time: 下午9:25
 * To change this template use File | Settings | File Templates.
 */
class IndexController extends BaseBackEndController
{
    public function indexAction()
    {
        AppLocal::init("zh_CN");//load library well
        $model=new UserModel();
        //{#{% set columns = {"列名1": "name" , "列名1": "id" ,"列名1": "" ,"列名1": function(data){}}%}#}
        $this->getView()->assign(array('hello'=>'hello ,backend!'));
    }
}