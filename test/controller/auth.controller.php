<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AuthController
{
    public function enter_auth()
    {
        if (count($_POST))
        {
            if ($_POST['_action'] === "enter")
            {
                $user = new User();
                if ($user->auth(System::post("login"), System::post("password"), System::post("remember", false)))
                {
                    header("Location: index.php?category=user&action=all");
                    die();
                }
                else
                {
                    header("Location: index.php?category=auth&action=enter");
                    die();
                }
            }
        }
        
        require_once 'view/auth/auth.php';
    }
}

