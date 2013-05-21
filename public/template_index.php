<?php
/*
 * template debug entrance
 */
define("ROOT" , dirname(__FILE__) . '/..');
// require bootstrap
require_once ROOT . '/application/Bootstrap.php';

// despatch the request
echo $_GET['b'];