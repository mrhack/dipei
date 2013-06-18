<?php
//////////translation    struct////////////
//预定义字段
//[0-10] [11-20]  [21-80] [81-90]  [91-120] [121-200]   [201-300]      [301-400]       [401-1000]
//  sex  dipei_tp  lang   familiar  contact   empty    travel-themes  travel_services   empty
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
        return null;
    }

    /**
     * @param $from array('zh_cn'=>'中国')
     * @param $tos array('en'=>'China','fr'=>'xckjsad')
     * 为word增加tp，用来标示意义
     */
    public function saveWord($from,$tos)
    {
        $record = $this->fetchOne($from);
        if(empty($record)){
            $record['_id']=$this->getNextId();
        }
        $record = array_merge($record, $tos);
        return $this->save($record);
    }

    public function translateWord($word,$fromLocal='zh',$toLocal=null){
        $record = $this->fetchOne(array($fromLocal=>new MongoRegex("/.*?$word.*/")));
        if(is_null($toLocal)){
            $toLocal=AppLocal::currentLocal();
        }
        if(!empty($record)){
            return $record[$toLocal];
        }else{
            $this->getLogger()->warn('unable translate word', func_get_args());
            return null;
        }
    }
}