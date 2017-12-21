<?php
//login
//password
//name
//surname
//middlename
//e-mail
//role
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Добавить пользователя</title>
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
        <h2>Введите данные:</h2>
        <form method="post" action="">
            <p><input type="text" name="surname" placeholder="Фамилия" required=""></p>
            <p><input type="text" name="name" placeholder="Имя" required=""></p>
            <p><input type="text" name="middlename" placeholder="Отчество"></p>
            <p><input type="text" name="login" placeholder="Логин" required=""></p>
            <p><input type="password" name="password" placeholder="Пароль" required=""></p>
            <p><input type="text" name="email" placeholder="E-mail" required=""></p>
            <p><label for="role">Выберите роль:</label><br>
            <select name="role" id="role">
                <?php
                foreach (User::$user_roles as $key => $value)
                {
                ?>
                <option value="<?=$key?>"><?=$value?></option>
                <?php
                }
                ?>
            </select></p>
            <input type="hidden" name="_action" value="add">
            <div style="float: left; margin-right: 10px;"><button type="submit">Добавить</button></div>
        </form>
        <form method="post" action="">
            <input type="hidden" name="_action" value="cansel">
            <div><button type="submit">Отмена</button></div>
        </form>
    </body>
</html>