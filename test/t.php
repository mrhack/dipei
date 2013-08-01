<?php
/*
 * entrance of dipei app
 */
require_once dirname(__FILE__).'/../script/Bootstrap.php';
define('TEST_DATA_DIR' , ROOT_DIR . '/test/data');

$tpl_dir = APPLICATION_PATH.'/views';
$t = new AppUploader("file");
var_dump( $t->getFileInfo());
?>

<form enctype="multipart/form-data" method="post">
    <input type="file" name="file" />
    <button>click</button>
</form>