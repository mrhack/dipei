<?php
/**
 * User: wangfeng
 * Date: 13-6-26
 * Time: 上午12:34
 * @method static RateModel getInstance()
 */
class RateModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '_id'=>new Schema('id',Constants::SCHEMA_INT),
            '$key'=>new Schema('money',Constants::SCHEMA_STRING),
            '$value'=>new Schema('rate',Constants::SCHEMA_STRING),
            'c_at'=>new Schema('date',Constants::SCHEMA_INT)//time
        );
    }

    public function saveRate($rate)
    {
        if(!empty($rate) && !isset($rate['c_at'])){//record time
            $rate['c_at']=time();
        }
        $this->insert($rate);
        $this->getLogger()->log('update new rate',$rate);
    }

    public function convertRate($num,$toRate=Constants::MONEY_EUR,$fromRate=null)
    {
        if($toRate == $fromRate){
            return $num;
        }
        if(is_null($fromRate)){
            $fromRate=AppLocal::currentMoney();
        }
        $rate=$this->fetchLastRate();
        $rate[Constants::MONEY_EUR]=1;
        if(isset($rate[$fromRate]) && !empty($rate[$fromRate])){
            $num = $num / $rate[$fromRate];
        }else{
            $this->getLogger()->warn('convert base failed',array('rate'=>$rate,'fromRate'=>$fromRate));
        }
        if(isset($rate[$toRate]) && !empty($rate[$toRate])){
            $num = $num * $rate[$toRate];
        }else{
            $this->getLogger()->warn('convert rate failed',array('rate'=>$rate,'toRate'=>$toRate));
        }
        return $num;
    }

    public function fetchLastRate()
    {
        static $lastRate=null;
        if(empty($lastRate)){
            $lastRate=$this->fetchOne(array('_id' => $this->getLastId()));
        }
        return $lastRate;
    }
}