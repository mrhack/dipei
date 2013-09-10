<?php
/**
 * User: wangfeng
 * Date: 13-6-30
 * Time: 下午9:09
 */
class BecomeController extends BaseController
{
    public function validateAuth()
    {
        return true;
    }

    public function indexAction($uid)
    {
    	$users = UserModel::getInstance()->fetch(
    		MongoQueryBuilder::newQuery()
                ->query(array('l_t'=>array('$gt'=>0)))
                ->limit(Constants::LIST_BECOME_USER_SIZE)
                ->sort(array('c_t'=>-1))
                ->build()
    		);
    	$this->assign(
    		array('user_list'=>array_column( $users , '_id' ))
    		);
    	$this->dataFlow->mergeUsers($users);
    	$this->assign($this->dataFlow->flow());
    }
}