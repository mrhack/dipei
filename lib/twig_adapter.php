
<?php
// Twig adapter for depei application

require_once ROOT . './vendor/twig/twig/lib/Twig/Autoloader.php';

Twig_Autoloader::register();
$loader = new Twig_Loader_String();


$TWIG_CONFIG = array(
    // template dir
    "path" => ROOT . "/application/views/",
    // template complie dir
    "complie" => ''
    );

$TWIG = new Twig_Environment($loader);