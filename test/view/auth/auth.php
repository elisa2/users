<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Авторизация</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h2>Авторизация</h2>
        <form method="post" action="">
            <p><input type="text" name="login" placeholder="Логин" required=""></p>
            <p><input type="password" name="password" placeholder="Пароль" required=""></p>
            <p><label for="remember">Запомнить меня </label>
            <input type="checkbox" name="remember" id="remember"></p>
            <input type="hidden" name="_action" value="enter">
            <div><button type="submit">Войти</button></div>
        </form>
    </body>
</html>