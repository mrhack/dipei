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

   public function query($cond)
   {
       $this->query=$cond;
       return $this;
   }

    public function sort($sort){
       $this->sort=$sort;
       return $this;
    }

    public function build()
    {
        $cond=array();
        $cond['$query']=empty($this->query)?array():$this->query;
        if(!empty($this->sort)){
            $cond['$orderBy']=$this->sort;
        }
        return $cond;
    }

}
