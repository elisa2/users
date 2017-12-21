<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Редактировать пользователя</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
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
        <h2>Редактируйте данные:</h2>
        <form method="post" action="">
            <p><input type="text" name="surname" value="<?=$user->surname?>" required=""></p>
            <p><input type="text" name="name" value="<?=$user->name?>" required=""></p>
            <p><input type="text" name="middlename" value="<?=$user->middlename?>"></p>
            <p><label for="old_password">Введите текущий пароль:</label><br>
            <p><input type="password" name="old_password" id="old_password"></p>
            <p><label for="new_password">Введите новый пароль:</label><br>
            <p><input type="password" name="new_password" id="new_password"></p>
            <p><label for="role">Выберите роль:</label><br>
            <select name="role" id="role">
                <?php
                foreach (User::$user_roles as $key => $value)
                {
                    $selected = "";
                    if ((int) $user->role === $key)
                    {
                        $selected = "selected";
                    }
                ?>
                <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                <?php
                }
                ?>
            </select></p>
            <input type="hidden" name="id" value="<?=$user->id?>">
            <input type="hidden" name="_action" value="edit">
            <div style="float: left; margin-right: 10px;"><button type="submit">Сохранить</button></div>
        </form>
        <form method="post" action="">
            <input type="hidden" name="_action" value="cansel">
            <div><button type="submit">Отмена</button></div>
        </form>
    </body>
</html>
