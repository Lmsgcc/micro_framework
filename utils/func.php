<?php

define('PATH_STRUCTURES', '../utils/structures/' );
define("UTILITY_PATH",'../utils/');
define('UTILITY_SEARCH_PATH', './utils/*/');
define('INTERFACES_PATH', './interfaces/');

function load_interfaces()
{
    $files = glob(INTERFACES_PATH . "*.php");
    if(!empty($files))
    {
        foreach($files as $f)
        {
            include_once $f;
        }
    }
}


function load_utility($name)
{
    
    $files = glob(UTILITY_SEARCH_PATH . "$name.php");
    if(!empty($files))
    {
        foreach($files as $f)
        {
            include_once $f;
        }
    }
}


?>