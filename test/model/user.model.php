<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class User 
{
    const ROLE_USER = 2;
    const ROLE_ADMIN = 1;
    
    public static $user_roles = [
        self::ROLE_ADMIN => "Администратор",
        self::ROLE_USER => "Пользователь"
    ];
    
    const ERROR_ADD = "{ERROR_ADD}";
    const ERROR_EDIT = "{ERROR_EDIT}";
    const ERROR_DELETE = "{ERROR_DELETE}";
    const FIELD_NOT_EXIST = "{FIELD_NOT_EXIST}";
    
    protected static $db;
    protected static $fields = [];
    protected static $field_types = [];
    
    public $data = [];
    public $is_loaded;
    
    public function __construct($id = NULL)
    {
        if (self::$fields === [])
        {
            self::init_fields();
        }
        
        if ($id !== NULL)
        {
            if ($this->one($id) === false)
            {
                $this->is_loaded = false;
            }
        }
    }
    
    public function __get($field)
    {
        if (isset($this->data[$field]))
        {
            return $this->data[$field];
        }
        else 
        {
            return self::FIELD_NOT_EXIST;
        }
    }
    
    public function __set($field, $value)
    {
        if ($field === "id")
        {
//            return self::ID_ACCESS_DENIED;
        }
        else 
        {
            if (in_array($field, self::$fields))
            {
                $value = self::clean_data($field, $value);
                $this->data[$field] = $value;
//                return $this->data[$field];
            }
            else 
            {
                return self::FIELD_NOT_EXIST;
            }
        }
    }
    
    public static function get_database()
    {
        if (self::$db === NULL)
        {
            $link = @mysqli_connect("localhost", "root", "", "test");

            if (mysqli_connect_errno())
            {
                printf("Error: " . mysqli_connect_error());
                die();
            }

            if (!@mysqli_set_charset($link, "utf8"))
            {
                printf("Error: " . mysqli_error($link));
            }
            
            self::$db = $link;
        }
        return self::$db;
    }
    
    protected static function table_name()
    {
        return "users";
    }
    
    protected static function init_fields()
    {
        $query = "DESCRIBE `" . self::table_name() . "`;";
        $result = mysqli_query(self::get_database(), $query);
        while ($row = mysqli_fetch_assoc($result))
        {
            self::$fields[] = $row['Field'];
            if (strpos($row["Type"], "("))
            {
                $array = explode("(", $row["Type"]);
                $row["Type"] = $array[0];
            }
            self::$field_types[$row["Field"]] = $row["Type"];
        }
    }
    
    protected static function get_fields()
    {
        if (self::$fields === [])
        {
            self::init_fields();
        }
        return self::$fields;
    }
    
    protected function query_fields()
    {
        $fields = static::get_fields();
        
        $result = "";
        
        foreach ($fields as $value)
        {
            if ($result !== "") 
            {
                $result .= ", ";
            }
            $result .= "`$value`";
        }
        return $result;
    }
    
    protected function query_values()
    {
        $fields = static::get_fields();
        $result = "";
        foreach ($fields as $value)
        {
            if ($result !== "") 
            {
                $result .= ", ";
            }
            if ((isset($this->data[$value])) && ($this->data[$value] !== NULL))
            {
                $result .= "'{$this->data[$value]}'";
            }
            else 
            {
                $result .= "NULL";
            }
        }
        return $result;
    }
    
    protected function query_update_values($array = [])
    {
        if ($array === [])
        {
            $fields = self::get_fields();
        }
        else 
        {
            foreach ($array as $value)
            {
                if (in_array($value, self::get_fields()))
                {
                    $fields[] = $value;
                }
            }
        }
        $result = "";
        foreach ($fields as $key => $value)
        {
            if ($key !== 0)
            {
                $result .= ", ";
            }
            if (($this->data[$value] === NULL) || (!isset($this->data[$value])))
            {
                $result .= "`" . $value . "` = NULL";
            }
            else 
            {
                $result .= "`" . $value . "` = '" . $this->data[$value] . "'";
            }
        }
        
        return $result;
    }
    
    protected static function clean_data($key, $value)
    {
        self::get_fields();
        if (self::$field_types[$key] === "int") 
        {
            $value = (int) $value;
        }
        else 
        {
            $value = mysqli_real_escape_string(self::get_database(), $value);
        }
        return $value;
    }
    
    public function load_data($array)
    {
        foreach ($array as $key => $value)
        {
            if (!in_array($key, self::$fields))
            {
                return self::FIELD_NOT_EXIST;
            }
            else 
            {
                $value = self::clean_data($key, $value);
                $this->data[$key] = $value;
            }
        }
        
        return true;
    }
    
    public function add()
    {
        $query = "INSERT INTO `" . self::table_name() . "` (" . $this->query_fields() . ") VALUES (" . $this->query_values() . ");";
        $result = mysqli_query(self::get_database(), $query);
        if ($result)
        {
            $this->data["id"] = mysqli_insert_id(self::get_database());
            return true;
        }
        else 
        {
            return self::ERROR_ADD;
        }
    }
    
    public function edit($array = [])
    {
        $query = "UPDATE `" . self::table_name() . "` SET " . $this->query_update_values($array) . " WHERE `id` = '$this->id';";
        $result = mysqli_query(self::get_database(), $query);
        if ($result)
        {
            return true;
        }
        else 
        {
            return self::ERROR_EDIT;
        }
    }
    
    public function delete()
    {
        $query = "DELETE FROM `" . self::table_name() . "` WHERE `id` = '$this->id' LIMIT 1;";
        $result = mysqli_query(self::get_database(), $query);
        if ($result)
        {
            return true;
        }
        else 
        {
            return self::ERROR_DELETE;
        }
    }
    
    public function one($id)
    {
        $id = (int) $id;
        $query = "SELECT * FROM `" . self::table_name() . "` WHERE `id` = '$id';";
        $result = mysqli_query(self::get_database(), $query);
        if ($row = mysqli_fetch_assoc($result))
        {
            return $this->load_data($row);
        }
        else 
        {
            return false;
        }
    }
    
    public static function all($field = NULL, $order = NULL, $fullname = NULL, $login = NULL, $admin = NULL, $user = NULL)
    {
        if (($field === NULL) || ($order === NULL))
        {
            $condition = FilterHelper::set_condition($fullname, $login, $admin, $user);
        }
        else 
        {
            if (in_array($field, self::get_fields()))
            {
                $condition = "ORDER BY `" . self::table_name() . "`.`$field` $order";
            }
            else 
            {
                return self::FIELD_NOT_EXIST;
            }
        }
        
        $query = "SELECT * FROM `" . self::table_name() . "` $condition;";
        $result = mysqli_query(self::get_database(), $query);
        $all = [];
        while ($row = mysqli_fetch_assoc($result))
        {
            $one = new User();
            if ($one->load_data($row) !== self::FIELD_NOT_EXIST)
            {
                $all[] = $one;
            }
        }
        return $all;
    }
    
    private static function generate_salt()
    {
        $length = 32;
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++)
        {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }
    
    private static function generate_password($password, $salt)
    {
        return md5(md5($password) . $salt);
    }
    
    public function create_password($password)
    {
        $this->salt = self::generate_salt();
        $this->password = self::generate_password($password, $this->salt);
    }
    
    public function check_password($password)
    {
        if ($this->salt === NULL) return false;
        
        $check_string = User::generate_password($password, $this->salt);
        
        if ($check_string === $this->password)
        {
            return true;
        }
        return false;
    }
    
    public function auth_flow()
    {
        if (isset($_SESSION['login']))
        {
            $login = $_SESSION['login'];
            $password = $_SESSION['password'];
            
            $this->get_user_by_login($login);
            
            if ($this->password === $password)
            {
                return true;
            }
            else 
            {
                $this->data['id'] = NULL; 
                $this->login = NULL; 
                $this->password = NULL;
                $this->salt = NULL;
                $this->role = NULL;
                return false;
            }
        }
        else 
        {
            if (isset($_COOKIE['login']))
            {
                $login = $_COOKIE['login'];
                $password = $_COOKIE['password'];
                
                $this->get_user_by_login($login);
                
                if ($this->password === $password)
                {
                    $_SESSION['login'] = $_COOKIE['login'];
                    $_SESSION['password'] = $_COOKIE['password'];
                    return true;
                }
                else 
                {
                    return false;
                }
            }
            else 
            {
                return false;
            }
        }
    }
    
    public function auth($login, $password, $remember)
    {
        $this->get_user_by_login($login);
        
        if ($this->check_password($password))
        {
            $_SESSION['login'] = $this->login;
            $_SESSION['password'] = $this->password;
            if ($remember)
            {
                setcookie("login", $this->login, time() + 60*60*24*7);
                setcookie("password", $this->password, time() + 60*60*24*7);
            }
            return true;
        }
        else 
        {
            return false;
        }
    }
    
    public function auth_exit()
    {
        if (isset($_COOKIE['login']))
        {
            setcookie("login", "", time() - 1);
            setcookie("password", "", time() - 1);
        }
        if (isset($_SESSION['login']))
        {
            unset($_SESSION['login']);
            unset($_SESSION['password']);
        }
        $this->data['id'] = NULL; 
        $this->login = NULL; 
        $this->password = NULL;
        $this->salt = NULL;
        $this->role = NULL;
    }
    
    public function get_user_by_login($login)
    {
        $login = mysqli_real_escape_string(self::get_database(), $login);
        $query = "SELECT * FROM `" . self::table_name() . "` WHERE `login` = '$login' LIMIT 1;";
        $result = mysqli_query(self::get_database(), $query);
        if ($row = mysqli_fetch_assoc($result)) 
        {
            $this->load_data($row);
        }
    }
}

