<?php
/**
 * user controller for backend
 */
class UserController extends  BaseBackEndController{

    public function flagSeed($uid,$sd){
        $userModel=UserModel::getInstance();
        $updateInfo=array(
            '_id'=>intval($uid),
            'sd'=>$sd
        );
        $userModel->update($updateInfo);
        $this->render_ajax(Constants::CODE_SUCCESS);
    }

    public function seedAction($uid)
    {
        $this->flagSeed($uid, 1);
        return false;
    }

    public function cancelSeedAction($uid)
    {
        $this->flagSeed($uid, 0);
        return false;
    }

    /**
     */
    public function makeQuery()
    {
        $query=array();
        $userInput=$this->getRequest()->getQuery('user');
        if(!empty($userInput)){
            $userInput = $this->getUserInfoFromQuery($userInput);
            //ignore other conditions
            return array('_id'=>$userInput['_id']);
        }

        $query = array_merge($query,
            $this->getMongoQuery('userType', self::QUERY_TYPE_INT,'l_t'),
            $this->getMongoQuery('isSeed',self::QUERY_TYPE_INT,'sd'),
            $this->getMongoQuery('location',self::QUERY_TYPE_INT,'lid'),
            $this->getTimeBetweenMongoQuery('create','c_t'),
            $this->getTimeBetweenMongoQuery('online','o_t')
        );
        return $query;
    }

    public function indexAction()
    {
        $pageSize = $this->getRequest()->getQuery('pageSize', 20);
        $page = $this->getRequest()->getQuery('page', 1);
        $query=$this->makeQuery();
        $builder = MongoQueryBuilder::newQuery()->skip(($page - 1) * $pageSize)->limit($pageSize)->query($query)->sort(array('c_t' => -1));
        $condition = $builder->build();
        $columns=array(
            'UID'=>'_id',
            '注册邮箱'=>'em',
            '昵称'=>'n',
            '小鲜类型'=>'l_t',
            '现居城市'=>'lid',
            '最后登陆时间/注册时间'=>'last_time',
            '操作'=>'options'
        );
        $users = UserModel::getInstance()->fetch($condition);
        $data_list=array();
        foreach($users as $user){
            $data=array();
            foreach($columns as $column){
                $data[$column] = $this->formatUserData($user, $column);
            }
            $data_list[]=$data;
        }

        $this->assign(array(
           'columns' =>$columns,
           'pagination' => array('total'=>UserModel::getInstance()->count($query)),
            'data_list'=>$data_list,
            'TERMS'=>$this->getDipeiTerms()
        ));
        //columns
        //data : column->value
        //pagination: count
    }

    public function formatUserData($user,$column)
    {
        switch($column){
            case 'n':
                return sprintf('<a href="http://www.xianlvke.com/detail/%s/" target="_blank">%s</a>',$user['_id'],$user['n']);
                break;
            case 'l_t':
                if(isset($user['l_t'])){
                    return $this->getDipeiTerm($user['l_t']);
                }else{
                    return '普通用户';
                }
            case 'lid':
                return $this->getLocationString($user['lid'], self::LOC_FORMAT_FULL_CITY);
            case 'last_time':
                return date('Y-m-d', $user['o_t']->sec) . '/' . date('Y-m-d', $user['c_t']->sec);
            case 'options':
                if($user['sd']){
                    return sprintf('<a href="#" data-action="cancelSeed" data-id="%s">取消种子标记</a>',$user['_id']);
                }else{
                    return sprintf('<a href="#" data-action="seed" data-id="%s">标记为种子</a>',$user['_id']);
                }
        }
       return $user[$column];
    }

}