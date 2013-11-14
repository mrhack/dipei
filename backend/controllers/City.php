<?php
/**
 * User: wangfeng
 * Date: 13-11-3
 * Time: 下午4:49
 */
class CityController extends BaseBackEndController
{
    public function makeQuery()
    {
        $query=array(
            'ptc'=>3
        );
        $location=$this->getRequest()->getRequest('location');
        if(!empty($location)){
            $locationInfo = LocationModel::getInstance()->fetchOne(array(
                '$or' => array(
                    array('_id' => intval($location)),
                    array('n' => $location) ,
                )
            ));
            if(LocationModel::getInstance()->isCountry($locationInfo)){
                $query['pt.1']=$locationInfo['_id'];
            }else{
                return array('_id'=>$locationInfo['_id']);
            }
        }
        $query = array_merge($query,
            $this->getMongoBetweenQuery('favNum','lk',self::QUERY_TYPE_INT),
            $this->getMongoBetweenQuery('dpNum','c.d',self::QUERY_TYPE_INT)
        );
        return $query;
    }

    public function imagesAction()
    {

    }

    public function addAction()
    {
        $this->doLocationManage('insert');
    }

    public function updateAction()
    {
        $this->doLocationManage('update');
    }

    public function doLocationManage($mode){
        $locationModel=LocationModel::getInstance();
        if($mode=='update'){
            $lid = intval($this->getRequest()->getRequest('lid'));
            $location = $locationModel->format($locationModel->fetchOne(array('_id' => $lid)));
        }else{
            $lid=0;
            $location=array();
        }

        if($this->getRequest()->isPost()){
            $locationInfo=$locationModel->format($this->getRequest()->getPost('Location'),true);
            $country = intval($this->getRequest()->getPost('country'));
            $countryLoc = $locationModel->fetchOne(array('_id' => $country));
            $path = $countryLoc['pt'];$path[]=$country;
            $locationInfo['pt'] = $path;
            if($mode=='update'){
                $locationInfo['_id']=$lid;
                $locationModel->updateLocation($locationInfo);
            }else{
                $lid=$locationModel->createLocation($locationInfo);
            }
            //ensure translation
            $this->translateLocation($lid);
            echo "success";
            return false;
        }

        $this->assign(array(
            'location'=>$location,
            'COUNTRIES'=>$locationModel->getCountries()
        ));
    }

    public function indexAction()
    {
        $pageSize = $this->getRequest()->getQuery('pageSize', 20);
        $page = $this->getRequest()->getQuery('page', 1);
        $query=$this->makeQuery();
        $builder=MongoQueryBuilder::newQuery()->skip(($page-1)*$pageSize)->limit($pageSize)->query($query)->sort(array('_id'=>-1));
        $condition = $builder->build();
        $columns=array(
            'LID'=>'_id',
            '城市名称'=>'n',
            '所属国家'=>'country',
            '城市简介'=>'dsc',
            '收藏数量'=>'lk',
            '小鲜数量'=>'c.p',
            '城市封面图片'=>'image',
            '状态即操作'=>'options'
        );

        $locs = LocationModel::getInstance()->fetch($condition);
        $data_list=array();
        foreach($locs as $loc){
            $data=array();
            foreach($columns as $column){
                $data[$column] = $this->formatLocationData($loc, $column);
            }
            $data_list[]=$data;
        }

        $this->assign(array(
            'columns' =>$columns,
            'pagination' => array('total'=>LocationModel::getInstance()->count($query)),
            'data_list'=>$data_list,
            'TERMS'=>$this->getDipeiTerms()
        ));
    }

    public function formatLocationData($loc,$column)
    {
        switch($column){
            case 'n':
                return $this->getLocationString($loc['_id'], self::LOC_FORMAT_CITY);
            case 'country':
                return $this->getLocationString($loc['pt'][1], self::LOC_FORMAT_COUNTRY);
            case 'c.p':
                return $loc['c']['p']+0;
            case 'lk':
                return $loc['lk']+0;
            case 'image':
                return sprintf('<img src="http://%s%s" width="200px" height="80px">',IMAGE_SERVER_URL,$loc['ims'][0]);
            case 'options':
                return sprintf('<a href="city/update?lid=%s" target="_blank">编辑资料</a>',$loc['_id']);
        }
        return $loc[$column];
    }
}