<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once 'system/system.class.php';
require_once 'functions.php';

spl_autoload_register("class_autoload");

if (isset($_GET['category']))
{
    $category = $_GET['category'];
}
else 
{
    $category = "user";
}

if (isset($_GET['action']))
{
    $action = $_GET['action'];
}
else 
{
    $action = "all";
}

$controller_class_name = ucfirst($category) . "Controller";
if (class_exists($controller_class_name))
{
    $controller_method_name = "{$action}_{$category}";
    if (method_exists($controller_class_name, $controller_method_name))
    {
        $controller_object = new $controller_class_name;
        $controller_object->$controller_method_name();
    }
    else 
    {
        error404();
    }
}
else 
{
    error404();
}





