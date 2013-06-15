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
    public $uids=array();//uids

    //-----------out------------------
    public $users=array();
    public $locations=array();
    public $translations=array();

    public function flow()
    {

    }

}