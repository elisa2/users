<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class System
{
    protected static $user;
    
    public static function get_user()
    {
        if (self::$user === NULL)
        {
            self::$user = new User();
            self::$user->auth_flow();
        }
        return self::$user;
    }
    
    public static function post($field = NULL, $default = NULL)
    {
        if ($field !== NULL)
        {
            if (is_array($field))
            {
                foreach ($field as $value)
                {
                    if (isset($_POST[$value]))
                    {
                        $data[$value] = $_POST[$value];
                    }
                    else 
                    {
                        $data[$value] = $default;
                    }
                }
                return $data;
            }
            else 
            {
                if (isset($_POST[$field]))
                {
                    if ($_POST[$field] !== "")
                    {
                        return $_POST[$field];
                    }
                    else 
                    {
                        return $default;
                    }
                }
                else 
                {
                    return $default;
                }
            }
        }
        else 
        {
            $result = $_POST;
            unset($result['_action']); 
            return $result;
        }
    }
    
    public static function set_message($message)
    {
        $_SESSION["message"] = $message;
    }

    public static function get_message()
    {
        if (isset($_SESSION["message"]))
        {
            $message = $_SESSION["message"];
            unset($_SESSION["message"]);
            return $message;
        }
        else 
        {
            return NULL;
        }
    }
}

