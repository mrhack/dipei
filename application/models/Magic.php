<?php
/**
 * User: wangfeng
 * Date: 13-10-11
 * Time: 下午11:03
 */
class MagicModel extends BaseModel
{
    private $collectionName;

    public function __construct($collectionName)
    {
        $this->collectionName=$collectionName;
    }

    public function getCollectionName()
    {
        return $this->collectionName;
    }

    public function getSchema()
    {
        //none schema
        return array();
    }
}