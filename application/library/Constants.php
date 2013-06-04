<?php
/**
 * 存放所有的静态变量
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午1:22
 */

abstract class Constants implements ErrorConstants,ModelConstants
{
    const CONN_MONGO_STRING='mongodb://localhost:27017/lepei?w=1';
    const DB_LEPEI = 'lepei';

    const PATH_LOG = '/data/logs/lepei';
}