<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:39
 *
 * @method static Mongo getInstance($server,$options=array())
 */

class AppMongo extends \Mongo
{
    use Strategy_Singleton;
}


