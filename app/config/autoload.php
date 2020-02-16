<?php

spl_autoload_register(function($classPath){
    $classPath=str_replace("\\", DIRECTORY_SEPARATOR, $classPath);
    $classPath=lcfirst($classPath);
    $classPath.=".php";
    require_once $classPath;
    //var_dump($classPath);
});
