<?php
/**
 * User: wangfeng
 * Date: 13-6-30
 * Time: 下午9:09
 * //TODO 浏览他的亦浏览了
 */
class DetailController extends BaseController
{
    public function validateAuth()
    {
        if($this->getRequest()->getActionName() == 'index'){
            $uid = $this->getRequest()->getParam('uid', 0);
            if(!UserModel::getInstance()->isValidId($uid)){
                $this->getLogger()->warn("not found uid $uid", array('request' => $this->getRequest(),'input'=>$_REQUEST));
                throw new AppException(Constants::CODE_NOT_FOUND);
            }
        }
        return true;
    }

    public function indexAction($uid)
    {
        $this->assignViewedLepei();
        $this->dataFlow->fuids[] = intval($uid);
        $this->assign(array('VUID' => $uid));
        

        //inc view count
        UserModel::getInstance()->update(array('$inc'=>array('vc'=>1)),array('_id'=>$uid));

        // set feeds
        $query = MongoQueryBuilder::newQuery()->query(array('uid'=>intval($uid)))
            ->sort(array('c_t' => -1))
            ->limit(Constants::LIST_FEED_SIZE);

        $feeds=FeedModel::getInstance()->fetch($query->build());
        $this->dataFlow->mergeFeeds($feeds);

        $data=$this->dataFlow->flow();
        $this->assign($data);

        //set viewed lepei
        $_lp = $this->getRequest()->getCookie('_lp', '');
        $viewedLepei = $_lp ? explode(',', $_lp ) : array();
        if(isset($data['USERS'][$uid])
            && (array_search($uid,$viewedLepei) === false)){

            $viewedLepei[]=$uid;
            if(count($viewedLepei) >4){
                $viewedLepei=array_slice($viewedLepei, -4);
            }
            $this->setCookie('_lp', $viewedLepei);
        }
    }
}