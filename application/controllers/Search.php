<?php
/**
 * User: wangfeng
 * Date: 13-7-13
 * Time: 下午5:32
 */
class SearchController extends BaseController
{
    public function validateAuth()
    {
        return true;
    }

    public function indexAction()
    {
        $input = $this->wrapInput(__METHOD__, $this->getRequest()->getRequest());
        $map=array(
            'sex'=>'sx',
            'lepei_type'=>'l_t',
            'travel_services'=>'ps.ts',
            'travel_themes'=>'ps.tm',
            'langs'=>'ils'
        );
        $query=array();
        foreach($map as $k=>$v){
            if(isset($input[$k])){
                $query[$v] = array('$in' => array_map('intval', $input[$k]));
            }
        }
        $lid = $input['lid'];
        $lid=123;
        $query['lpt'] = intval($lid);
        $page = max(1, intval($this->getRequest()->getRequest('page', 1)));
        $pageSize=Constants::LIST_PAGE_SIZE;
        $userModel=UserModel::getInstance();
        $mongoQuery=MongoQueryBuilder::newQuery()->query($query)->skip(($page-1)*$pageSize)->limit($pageSize)->build();
        $count=$userModel->count($query);
        $users = $userModel->fetch($mongoQuery);
        $this->dataFlow->mergeUsers($users);

        $this->assign(array('lepei_list'=>array_keys($users)));
        $this->assign($this->dataFlow->flow());
    }
}