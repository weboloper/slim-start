<?php

$modulePath = __DIR__.'/../modules/';
// $module = __DIR__.'/../app/modules/' . $modules.'/routes';
$_directories = glob($modulePath . "*");
$routeDirs = [];

// print_r($routeDirs);

foreach ($_directories as $dir) {

    $modules = str_replace($modulePath, '', $dir);
    $module = __DIR__.'/../modules/' . $modules.'/Routes';
    // $module = __DIR__.'/../app/modules/' . $modules.'/routes';

    $routeDirs[] = $module;
}
// print_r($routeDirs);
foreach ($routeDirs as $route){
    $routePath =   $route.'/Routes.php';
    if(file_exists($routePath)){
        include $routePath;
    }
}

 