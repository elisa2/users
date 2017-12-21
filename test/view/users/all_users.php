<?php
$fields = [
    "login" => "логину",
    "surname" => "ФИО",
    "role" => "роли"
];
$order = [
    "asc" => "А-Я (A-Z)",
    "desc" => "Я-А (Z-A)"
];

if (isset($_SESSION['fullname']))
{
    $fullname = $_SESSION['fullname'];
}
else 
{
    $fullname = "";
}
if (isset($_SESSION['login_filter']))
{
    $login_filter = $_SESSION['login_filter'];
}
else 
{
    $login_filter = "";
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Список пользователей</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div style="float: right;">
            <form method="post" action="">
                <p><label for="sort">Сортировать по:</label><br>
                <select name="field" id="sort">
                <?php
                foreach ($fields as $key => $value)
                {
                    $selected_field = "";
                    if (isset($_SESSION['field']))
                    {
                        if ($_SESSION['field'] === $key)
                        {
                            $selected_field = "selected";
                        }
                    }
                ?>
                    <option <?=$selected_field?> value="<?=$key?>"><?=$value?></option>
                <?php
                }
                ?>
                </select>
                <select name="order">
                <?php
                foreach ($order as $key => $value)
                {
                    $selected_order = "";
                    if (isset($_SESSION['order']))
                    {
                        if (strtolower($_SESSION['order']) === $key)
                        {
                            $selected_order = "selected";
                        }
                    }
                ?>
                    <option <?=$selected_order?> value="<?=$key?>"><?=$value?></option>
                <?php
                }
                ?>
                </select>
                </p>
                <input type="hidden" name="_action" value="sort">
                <div><button type="submit">Сортировать</button></div>
            </form>
            <form method="post" action="">
                <input type="hidden" name="_action" value="cansel_sort">
                <div><button type="submit">Отмена</button></div>
            </form><br><br>
            
            <p>Фильтр</p>
            <form method="post" action="">
                <p><label for="fullname">ФИО: </label>
                <input type="text" name="fullname" value="<?=$fullname?>" id="fullname"></p>
                <p><label for="login">Логин: </label>
                <input type="text" name="login" value="<?=$login_filter?>" id="login"></p>
                <?php
                foreach (User::$user_roles as $key => $value)
                {
                    $num_admin = User::ROLE_ADMIN;
                    $num_user = User::ROLE_USER;
                    ${"check_$num_admin"} = "";
                    ${"check_$num_user"} = "";
                            
                    if (isset($_SESSION['admin']))
                    {
                        ${"check_$num_admin"} = "checked";
                    }
                    if (isset($_SESSION['user']))
                    {
                        ${"check_$num_user"} = "checked";
                    }
                ?>
                    <p><label for="<?=$key?>"><?=$value?> </label>
                    <input <?=${"check_$key"}?> type="checkbox" name="<?=$key?>" id="<?=$key?>"></p>
                <?php
                }
                ?>
                <input type="hidden" name="_action" value="filter">
                <div><button type="submit">Показать</button></div>
            </form>
            <form method="post" action="">
                <input type="hidden" name="_action" value="cansel_filter">
                <div><button type="submit">Отмена</button></div>
            </form>
        </div>
        <div style="float: left; margin-right: 10px;">Вы вошли под логином "<?=System::get_user()->login?>"</div>
        <form method="post" action="">
            <input type="hidden" name="_action" value="exit">
            <div><button type="submit">Выйти</button></div>
        </form>
        <?php
        $message = System::get_message();
        if ($message !== NULL)
        {
        ?>
        <br><em><?=$message?></em>
        <?php
        }
        ?>
        <h2>Все пользователи</h2>
        <?php
        if (count($users))
        {
        ?>
        <table>
            <col width="150">
            <col width="150">
            <col width="150">
            <col width="150">
            <col width="300">
            <tr>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Логин</th>
                <th>Роль</th>
                <th>Действия</th>
            </tr>
            <?php
            foreach ($users as $user)
            {
            ?>
            <tr>
                <td style="text-align: center;"><?=$user->surname?></td>
                <td style="text-align: center;"><?=$user->name?></td>
                <td style="text-align: center;"><?=$user->login?></td>
                <td style="text-align: center;"><?=User::$user_roles[$user->role]?></td>
                <td style="text-align: center;">
                    <a href="index.php?category=user&action=view&id=<?=$user->id?>">Просмотреть</a> 
                    / <a href="index.php?category=user&action=edit&id=<?=$user->id?>">Редактировать</a>
                    / <a href="index.php?category=user&action=delete&id=<?=$user->id?>">Удалить</a>
                </td>
            </tr>
            <?php
            }
            ?>
        </table>
        <?php
        }
        else 
        {
            echo "<p>Нет пользователей</p>";
        }
        ?>
        <p><a href="index.php?category=user&action=add">Добавить пользователя</a></p>
    </body>
</html>
