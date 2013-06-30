<?php
/**
 * @desc: desc
 * @date:
 * @author: hdg1988@gmail.com
 * useage : php xxx.php dir tarfile
 */
require_once "../script/common.php";

$dir = $argv[1];
$tar = $argv[2];

// $config
$config = array(
    "width" => 14,
    "height" => 11,
    "top" => 2,
    "left" => 1
    );
// count the file num
$num = count( glob( $dir . "/*.png") );
