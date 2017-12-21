<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Удалить пользователя</title>
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
        <h2>Вы действительно хотите удалить данного пользователя?</h2>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?=$user->id?>">
            <input type="hidden" name="_action" value="delete">
            <div style="float: left; margin-right: 10px;"><button type="submit">Удалить</button></div>
        </form>
        <form method="post" action="">
            <input type="hidden" name="_action" value="cansel">
            <div><button type="submit">Отмена</button></div>
        </form>
    </body>
</html>
