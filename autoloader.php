<?php 
define('BASE_PATH', realpath(dirname(__FILE__)));

function myAutoloader($class)
{
    $filename = BASE_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
    include($filename);
}
spl_autoload_register('myAutoloader');