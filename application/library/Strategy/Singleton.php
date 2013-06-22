<?php
/**
 * User: wangfeng
 * Date: 13-5-27
 * Time: 上午3:42
 */
trait Strategy_Singleton
{
    public static function getInstance() {
        static $_instance = NULL;
        $class = __CLASS__;
        if($_instance === null){
            $reflect = new ReflectionClass($class);
            $_instance=$reflect->newInstanceArgs(func_get_args());
        }
        return $_instance;
    }

    public function __clone() {
        trigger_error('Cloning '.__CLASS__.' is not allowed.',E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Unserializing '.__CLASS__.' is not allowed.',E_USER_ERROR);
    }
}