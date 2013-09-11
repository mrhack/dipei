<?php
/**
 * @name IndexController
 * @author wangfeng
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

    public function validateAuth()
    {
        return true;//always ok
    }

	public function indexAction() {
        $this->assignViewedLepei();
        //append search locations
        $this->renderSearch();
        //right
        $userModel=UserModel::getInstance();
        $queryBuilder=new MongoQueryBuilder();
        $locUserList=array();
        foreach($locList as $lid){
            $users=$locUserList[$lid] = $userModel->fetch(
                $queryBuilder->query(array('lpt' => $lid))->sort(array('vc' => -1))->limit(5)->comment('getLepeiUnderLid')->build()
            );
            $locUserList[$lid] = array_keys($users);
            $this->dataFlow->mergeUsers($users);
        }
        $this->assign(array('loc_user_list' => $locUserList));
        $this->dataFlow->lids = array_merge($this->dataFlow->lids, $locList);
        $this->getView()->assign(array('loc_list' => $locList));

        // get like status
        if( $this->userId ){
            $likes = LikeModel::getInstance()->fetch(
                MongoQueryBuilder::newQuery()
                    ->query(array('uid'=> $this->userId , 'oid'=>array('$in'=>$locList) , 'tp'=>Constants::LIKE_LOCATION))
                    ->build()
                );
            $this->assign(array('likes'=> array_column(LikeModel::getInstance()->formats( $likes , true ) , null , 'oid' ) ));
        }

        $this->getView()->assign($this->dataFlow->flow());
//        var_dump($this->getView()->getAssigned());
//        return false;
	}
}
