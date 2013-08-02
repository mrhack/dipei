<?php
require_once '../../application/library/AppValidator.php';
require_once '../../application/library/AppLocal.php';
require_once '../../application/library/AppHelper.php';

$valids = array(
     // string
     'n' => array("string:required","maxlength" => 20,"minlength" => 2),
     // range , if s is not null , it must be 0 or 1
     's' => array("enumerate:required",array( 0 , 1 ,2)),
     // number
     'age' => array("number", "min" => 10,"max" => 30,"message"=> _e("age must bigger than 10 and less than 30")),
     // email , use regexp to match
     'em' => array("email"),
     // regexp ,
     'p.c' => array("regexp","/.{2,}/s" ),
 );

$data = array( "em"=>"aa -@a.cn", "age"=>"12" , "p"=>array('c'=>"a"));
AppValidator::valid( $data , $valids );