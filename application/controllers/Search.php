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
        $lid = intval($this->getRequest()->getRequest('lid', 0));
        if(!LocationModel::getInstance()->isValidId($lid)){
            $this->getLogger()->warn("not found lid $lid",array('request'=>$this->getRequest(),'input'=>$_REQUEST));
            return false;
        }
        return true;
    }

    public function indexAction()
    {
        $input = $this->getRequest()->getRequest();
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
        $lid = intval($input['lid']);
        $query['lpt'] = $lid;
        $page = max(1, intval($this->getRequest()->getRequest('page', 1)));
        $pageSize=Constants::LIST_PAGE_SIZE;
        $userModel=UserModel::getInstance();
        $mongoQuery=MongoQueryBuilder::newQuery()->query($query)->skip(($page-1)*$pageSize)->limit($pageSize)->build();
        $count=$userModel->count($query);
        $users = $userModel->fetch($mongoQuery);

        $this->dataFlow->mergeUsers($users);
        $this->dataFlow->flids[]=$lid;

//        var_dump($mongoQuery);

        $this->assign(array(
            'LID'=>$lid,
            'lepei_list'=>array_keys($users),
            'lepei_count'=>$count
        ));
        $this->assign($this->dataFlow->flow());
    }
}