<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserController
{
    public function __construct()
    {
        if (System::get_user()->data === [])
        {
            header("Location: index.php?category=auth&action=enter");
            die();
        }
        
        if (count($_POST))
        {
            if ($_POST['_action'] === "exit")
            {
                $user = new User();
                $user->auth_exit();
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
    }
    
    public function add_user()
    {
        if (System::get_user()->role === User::ROLE_USER)
        {
            System::set_message("Добавлять новых пользователей может только администратор");
            header("Location: index.php?category=user&action=all");
            die();
        }
        
        if (count($_POST))
        {
            if ($_POST["_action"] === "add")
            {
                if (filter_var(System::post("email"), FILTER_VALIDATE_EMAIL) === false)
                {
                    System::set_message("Введенный вами e-mail некорректен");
                    header("Location: index.php?category=user&action=add");
                    die();
                }
                
                $user = new User();
                $user->create_password(System::post("password"));
                $user->load_data(System::post(["surname", "name", "middlename", "login", "email", "role"], ""));
                
                if ($user->add() !== USER::ERROR_ADD)
                {
                    header("Location: index.php?category=user&action=all");
                    die();
                }
                else 
                {
                    System::set_message("Не удалось добавить нового пользователя");
                    header("Location: index.php?category=user&action=add");
                    die();
                }
            }
            
            if ($_POST["_action"] === "cansel")
            {
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
        
        require_once 'view/users/add_user.php';
    }
    
    public function edit_user()
    {
        if (System::get_user()->role === User::ROLE_USER)
        {
            if (System::get_user()->id !== (int) $_GET['id'])
            {
                System::set_message("Вы можете редактировать только свой профиль");
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
            
        if (count($_POST))
        {
            if ($_POST["_action"] === "edit")
            {
                $id = System::post("id");
                $user = new User($id);
                if (System::post("old_password") !== NULL)
                {
                    $old_password = System::post("old_password");
                    $new_password = System::post("new_password");
                    if (($user->check_password($old_password)) && ($new_password !== NULL))
                    {
                        $user->create_password($new_password);
                    }
                    else 
                    {
                        System::set_message("Пароль введен неверно");
                        header("Location: index.php?category=user&action=edit&id=$id");
                        die();
                    }
                }
                
                $user->load_data(System::post(["surname", "name", "middlename", "role"], ""));
                if ($user->edit(["surname", "name", "middlename", "role", "salt", "password"]) !== USER::ERROR_EDIT)
                {
                    
                    header("Location: index.php?category=user&action=all");
                    die();
                }
                else 
                {
                    System::set_message("Не удалось редактировать профиль пользователя");
                    header("Location: index.php?category=user&action=edit&id=$id");
                    die();
                }
            }
            
            if ($_POST["_action"] === "cansel")
            {
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
        
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
            $user = new User($id);
            if ($user->is_loaded === false)
            {
                error404("пользователь с таким id не найден");
            }
        }
        else 
        {
            error404("no id");
        }
        require_once 'view/users/edit_user.php';
    }
    
    public function delete_user()
    {
        if (System::get_user()->role === User::ROLE_USER)
        {
            System::set_message("Удалять пользователей может только администратор");
            header("Location: index.php?category=user&action=all");
            die();
        }
        else 
        {
            if (System::get_user()->id === (int) $_GET['id'])
            {
                System::set_message("Вы не можете удалить свой профиль");
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
        
        if (count($_POST))
        {
            if ($_POST["_action"] === "delete")
            {
                $id = System::post("id");
                $user = new User($id);
                if ($user->delete() !== USER::ERROR_DELETE)
                {
                    header("Location: index.php?category=user&action=all");
                    die();
                }
                else 
                {
                    System::set_message("Не удалось удалить пользователя");
                    header("Location: index.php?category=user&action=delete&id=$id");
                    die();
                }
            }
            
            if ($_POST["_action"] === "cansel")
            {
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
        
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
            $user = new User($id);
            if ($user->is_loaded === false)
            {
                error404("пользователь с таким id не найден");
            }
        }
        else 
        {
            error404("no id");
        }
        require_once 'view/users/delete_user.php';
    }
    
    public function view_user()
    {
        if (isset($_GET['id']))
        {
            $id = $_GET['id'];
            $user = new User($id);
            if ($user->is_loaded === false)
            {
                error404("пользователь с таким id не найден");
            }
        }
        else 
        {
            error404("no id");
        }
        require_once 'view/users/view_user.php';
    }
    
    public function all_user()
    {
        if (count($_POST))
        {
            if ($_POST['_action'] === "sort")
            {
                unset($_SESSION['fullname']);
                unset($_SESSION['login_filter']);
                unset($_SESSION['admin']);
                unset($_SESSION['user']);
                
                $_SESSION['field'] = System::post("field");
                $_SESSION['order'] = strtoupper(System::post("order"));

                header("Location: index.php?category=user&action=all");
                die();
            }
            
            if ($_POST['_action'] === "cansel_sort")
            {
                unset($_SESSION['field']);
                unset($_SESSION['order']);
                
                header("Location: index.php?category=user&action=all");
                die();
            }
            
            if ($_POST['_action'] === "filter")
            {
                unset($_SESSION['field']);
                unset($_SESSION['order']);
                
                $_SESSION['fullname'] = System::post("fullname");
                $_SESSION['login_filter'] = System::post("login");
                $_SESSION['admin'] = System::post(User::ROLE_ADMIN);
                $_SESSION['user'] = System::post(User::ROLE_USER);
                
                header("Location: index.php?category=user&action=all");
                die();
            }
            
            if ($_POST['_action'] === "cansel_filter")
            {
                unset($_SESSION['fullname']);
                unset($_SESSION['login_filter']);
                unset($_SESSION['admin']);
                unset($_SESSION['user']);
                
                header("Location: index.php?category=user&action=all");
                die();
            }
        }
        
        if ((isset($_SESSION['field'])) && (isset($_SESSION['order'])))
        {
            $users = User::all($_SESSION['field'], $_SESSION['order']);
        }
        else if ((isset($_SESSION['fullname'])) || (isset($_SESSION['login_filter'])) || (isset($_SESSION['admin'])) || (isset($_SESSION['user'])))
        {
            $users = User::all(NULL, NULL, $_SESSION['fullname'], $_SESSION['login_filter'], $_SESSION['admin'], $_SESSION['user']);
        }
        else 
        {
            $users = User::all();
        }
        
        require_once 'view/users/all_users.php';
    }
}

