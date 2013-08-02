<?php
//////////translation    struct////////////
//预定义字段
//[0-10] [11-20]  [21-80] [81-90]  [91-120] [121-200]   [201-300]      [301-400]       [401-1000]
//  sex  dipei_tp  lang   familiar  contact   money    travel-themes  travel_services   empty
//其它
//[1000-100,0000]  [100,0001-,]
//lid+1000          custom

/**
 * translateList:
 * sex,
 * location name,
 * language,
 * travel_theme,
 * travel_service
 *
 * User: wangfeng
 * Date: 13-6-12
 * Time: 下午8:23
 * @method static TranslationModel getInstance()
 */
class TranslationModel extends BaseModel
{
    use Strategy_Singleton;

    public function getSchema()
    {
        return array(
            '$key'=>new Schema('local',Constants::SCHEMA_STRING),
            '$value'=>new Schema('value',Constants::SCHEMA_STRING)
        );
    }

    /**
     * @param $word array('zh_cn'=>'中国')
     */
    public function fetchOrSaveCustomWord($word)
    {
        $record = $this->fetchOne($word);
        if(empty($record)){
            $record=$word;
            $record['_id']=$this->getNextId();
            $this->insert($record);
        }
        return $record['_id'];
    }

    public function fetchWord($word,$local=null){

    }

    public function translateWord($record,$toLocal=null){
        if(is_null($toLocal)){
            $toLocal=AppLocal::currentLocal();
        }
        if(!empty($record)){
            do{
                if(isset($record[$toLocal])){
                    return $record[$toLocal];
                }
                if(strpos($toLocal,'_') === false){
                    break;
                }
            }while($toLocal=preg_replace('/(.*)_\w+/','$1',$toLocal));
            $this->getLogger()->warn('not found fit translate from local '.$toLocal,func_get_args());
            if(isset($record[AppLocal::defaultLocal()])){
                return $record[AppLocal::defaultLocal()];
            }else{
                return array_shift(array_values($record));
            }
        }else{
            $this->getLogger()->warn('unable translate word', func_get_args());
            return null;
        }
    }
}