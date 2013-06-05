<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\ChromePHPHandler;
/**
 * Factory of logger
 * User: wangfeng
 * Date: 13-5-28
 * Time: 下午10:42
 */
class AppLogger
{
   public static function newLogger($name,$logPath)
   {
       $logger=new Logger($name);
       $logger->pushHandler(new FirePHPHandler());
       $logger->pushHandler(new ChromePHPHandler());
       $logger->pushHandler(new StreamHandler($logPath));
       return $logger;
   }
}