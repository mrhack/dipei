<?php
/**
 * User: wangfeng
 * Date: 13-7-1
 * Time: 下午10:53
 */
require_once __DIR__ . '/Bootstrap.php';
require_once __DIR__ . '/faker/src/autoload.php';

$users = UserModel::getInstance()->fetch();
$locations = LocationModel::getInstance()->fetch();
$postModel=PostModel::getInstance();
$replyModel=ReplyModel::getInstance();
$count=1000;
while($count--){
    $faker = \Faker\Factory::create();
    try{
        $post=array(
            'uid'=>array_rand($users),
            't'=>$faker->sentence,
            'c'=>$faker->text,
            'lid'=>array_rand($locations),
            'tp'=>rand(Constants::FEED_TYPE_POST,Constants::FEED_TYPE_QA),
            's'=>rand(0,1)?Constants::STATUS_PASSED:Constants::STATUS_NEW,
            'vc'=>rand(50,5000),
            'lk'=>rand(50,5000)
        );
        $pid=$postModel->addPost($post);

        $replyCount = rand(0, 40);
        $replyIds=array();
        while($replyCount--){
            $reply=array(
                'uid'=>array_rand($users),
                'c'=>$faker->text,
                'pid'=>$pid,
                's'=>rand(0,1)?Constants::STATUS_PASSED:Constants::STATUS_NEW
            );
            if(rand(1,100)<=20){
                $reply['rid']=$replyIds[array_rand($replyIds)];
            }
            $replyIds[]=$replyModel->addReply($reply);
        }
        getLogger(__FILE__)->info("add $count -".count($replyIds));
    }catch (AppException $ex){
        getLogger(__FILE__)->error($ex->getMessage(),$ex->getContext());
    }
}
