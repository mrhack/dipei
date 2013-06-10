<?php
require_once '../../application/library/AppLocal.php';
require_once '../../application/library/AppHelper.php';

AppLocal::getProperties();
echo AppLocal::getString("please enter you #[hahah] name" , array("hahah"=>"aaaaaa"));
echo AppLocal::getString("hello" , array("hahah"=>"aaaaaa"));