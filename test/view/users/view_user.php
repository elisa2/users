<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Просмотр пользователя</title>
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
        <h2>Профиль пользователя</h2>
        <p>Фамилия: <?=$user->surname?></p>
        <p>Имя: <?=$user->name?></p>
        <?php
        if ($user->middlename !== "")
        {
        ?>
        <p>Отчество: <?=$user->middlename?></p>
        <?php
        }
        ?>
        <p>Логин: <?=$user->login?></p>
        <p>Роль: <?=User::$user_roles[$user->role]?></p>
        <p>E-mail: <?=$user->email?></p>
        <p>
            <a href="index.php?category=user&action=all">Вернуться к списку</a> 
            / <a href="index.php?category=user&action=edit&id=<?=$user->id?>">Редактировать</a>
            / <a href="index.php?category=user&action=delete&id=<?=$user->id?>">Удалить</a>
        </p>
    </body>
</html>
