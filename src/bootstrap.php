<?php

if(strpos($_SERVER['HTTP_HOST'], "helenparkhurst.net") !== false) {
    define('LOCAL', false);
} else {
    define('LOCAL', true);
}
//print LOCAL;
    
$Ipath = get_include_path();
set_include_path($Ipath .
            PATH_SEPARATOR . "/src/" .
            PATH_SEPARATOR . "/src/example");


