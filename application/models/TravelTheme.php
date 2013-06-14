<?php
/**
 * User: wangfeng
 * Date: 13-6-14
 * Time: 下午9:23
 */
class TravelTheme extends BaseModel
{
   public function getSchema()
   {
       return array(
           'tid'=>new Schema('translate_id',Constants::SCHEMA_INT),
       );
   }
}