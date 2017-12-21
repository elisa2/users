<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FilterHelper
{
    public static function set_condition($fullname = NULL, $login = NULL, $admin = NULL, $user = NULL)
    {
        $condition = "WHERE";
        if ($fullname !== NULL)
        {
            $array = explode(" ", $fullname); 
            if (isset($array[0])) 
            {
                $condition .= " `surname` = '$array[0]'";
            }
            if (isset($array[1]))
            {
                $condition .= " AND `name` = '$array[1]'";
            }
            if (isset($array[2]))
            {
                $condition .= " AND `middlename` = '$array[2]'";
            }
        }
        
        if (($login !== NULL) || ($admin !== NULL) || ($user !== NULL))
        {
            if (($condition !== "WHERE") && ($login !== NULL))
            {
                $condition .= " OR";
            }

            if ($login !== NULL)
            {
                $condition .= " `login` = '$login'";
            }

            if (($condition !== "WHERE") && ($admin !== NULL))
            {
                $condition .= " OR";
            }

            if ($admin !== NULL)
            {
                $role_admin = User::ROLE_ADMIN;
                $condition .= " `role` = '$role_admin'";
            }

            if (($condition !== "WHERE") && ($user !== NULL))
            {
                $condition .= " OR";
            }
            
            if ($user !== NULL)
            {
                $role_user = User::ROLE_USER;
                $condition .= " `role` = '$role_user'";
            }
        }
        else 
        {
            if ($fullname === NULL)
            {
                $condition .= " 1";
            }
        }
        
        return $condition;
    }
}

