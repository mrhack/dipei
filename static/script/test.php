<?php

require_once './refresh-cache.php';


if( BuildPublish::cleanPath("e://11/22/33\\44\../55/66.png")
    == 'e:/11/22/33/55/66.png' )
    echo 'true';
else
    echo 'false';