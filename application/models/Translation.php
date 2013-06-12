<?php
/**
 * User: wangfeng
 * Date: 13-6-12
 * Time: 下午8:23
 */
class TranslationModel extends BaseModel
{
    public function getSchema()
    {
        return null;
    }

    /**
     * @param $from array('cn'=>'中国')
     * @param $tos array('en'=>'China','fr'=>'xckjsad')
     */
    public function saveWord($from,$tos)
    {
        $record = $this->fetchOne($from);
        if(empty($record)){
            $record['_id']=$this->getNextId();
        }
        $record = array_merge($record, $tos);
        $this->save($record);
    }

    public function translateWord($word,$fromLocal='zh',$toLocal=null){
        $record = $this->fetchOne(array($fromLocal=>$word));
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