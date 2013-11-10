<?php
/**
 * User: wangfeng
 * Date: 13-11-3
 * Time: 下午4:49
 */
class MessageController extends BaseBackEndController
{
    public function makeQuery()
    {
        $query=array(
            'uid'=>array('$gt'=>0)
        );
        $sender=$this->getRequest()->getRequest('sender');
        if(!empty($sender)){
            $sendUser = $this->getUserInfoFromQuery($sender);
            $query['uid'] = $sendUser['_id'];
        }
        $receiver=$this->getRequest()->getRequest('receiver');
        if(!empty($receiver)){
            $receiverUser = $this->getUserInfoFromQuery($receiver);
            $query['tid']=$receiverUser['_id'];
        }
        $query = array_merge($query,
            $this->getTimeBetweenMongoQuery('create','c_t'),
            $this->getMongoQuery('read',self::QUERY_TYPE_INT,'r')
        );
//        var_dump($query);exit;
        return $query;
    }

    public function indexAction()
    {
        $pageSize = $this->getRequest()->getQuery('pageSize', 20);
        $page = $this->getRequest()->getQuery('page', 1);
        $query=$this->makeQuery();
        $builder=MongoQueryBuilder::newQuery()->skip(($page-1)*$pageSize)->limit($pageSize)->query($query)->sort(array('_id'=>-1));
        $condition = $builder->build();
        $columns=array(
            '发信日期'=>'c_t',
            '发信人/类型'=>'sender',
            '收信人/类型'=>'receiver',
            '状态'=>'read',
            '私信正文'=>'c',
        );

        $msgs = MessageModel::getInstance()->fetch($condition);
        $data_list=array();
        foreach($msgs as $msg){
            $data=array();
            foreach($columns as $column){
                $data[$column] = $this->formatMessage($msg, $column);
            }
            $data_list[]=$data;
        }

        $this->assign(array(
            'columns' =>$columns,
            'pagination' => array('total'=>MessageModel::getInstance()->count($query)),
            'data_list'=>$data_list,
//            'TERMS'=>$this->getDipeiTerms()
        ));
    }

    public function formatMessage($msg,$column)
    {
        switch($column){
            case 'c_t':
                return date('Y-m-d H:i', $msg['c_t']->sec);
                break;
            case 'read':
                return $msg['r']?'已读':'未读';
            case 'sender':
                return $this->formatUser($msg['uid']);
            case 'receiver':
                return $this->formatUser($msg['tid']);
        }
        return $msg[$column];
    }

    private function formatUser($uid)
    {
        $user = UserModel::getInstance()->fetchOne(array('_id' => $uid));
        $type=UserModel::getInstance()->isLepei($user)?'小鲜':'普通用户';
        return sprintf('<a href="http://www.xianlvke.com/detail/%s/" target="_blank">%s</a><br>%s',$user['_id'],$user['n'],$type);
    }
}
