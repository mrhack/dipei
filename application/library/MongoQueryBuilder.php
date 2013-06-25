<?php
/**
 * User: wangfeng
 * Date: 13-6-24
 * Time: 下午7:16
 */
class MongoQueryBuilder{

   use AppComponent;

   public $query=array();
   public $sort;
   public $limit;
   public $skip;

   public function query($cond)
   {
       $this->query=$cond;
       return $this;
   }

   public function skip($skip){
       $this->skip=$skip;
       return $this;
   }

    public function sort($sort){
       $this->sort=$sort;
       return $this;
    }

    public function limit($limit){
        $this->limit=$limit;
        return $this;
    }

    public function build()
    {
        $cond=array();
        $cond['$query']=empty($this->query)?array():$this->query;
        if(!empty($this->sort)){
            $cond['$orderBy']=$this->sort;
        }
        if(!empty($this->limit)){
            //extended mongo find
            $cond['$limit']=$this->limit;
        }
        if(!empty($this->skip)){
            //extended mongo find
            $cond['$skip']=$this->skip;
        }
        return $cond;
    }

}
