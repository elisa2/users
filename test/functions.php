<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function class_autoload($class_name)
{
    if (substr($class_name, -10) === "Controller")
    {
        $file = "controller/" . strtolower(substr($class_name, 0, -10)) . ".controller.php";
    }
    else if (substr($class_name, -6) === "Helper")
    {
        $file = "system/helper/" . strtolower(substr($class_name, 0, -6)) . ".helper.php";
    }
    else
    {
        $file = "model/" . strtolower($class_name) . ".model.php";
    }
    if (file_exists($file))
    {
        require_once "$file";
    }
    else 
    {
        error404("ошибка");
    }
}

function error404($message = "")
{
    header("HTTP/1.1 404 Not Found");
    die("Error 404: $message");
}

