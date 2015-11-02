<?php
include_once $_SERVER['DOCUMENT_ROOT']. '/includes/funcs.inc.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Регистрация!</title>
    <link rel="stylesheet" href="/bootstrap.min.css" type="text/css" media="all">
    <style type="text/css">
         .container {
         background-color: lightskyblue;
         }
    </style>
</head>
<body>
<div class="container">
    <div class="row text-center">
        <img src="/img/logo.png">
    </div>
    <div class="row">
<h1 class="h1 text-center">Общайтесь прямо сейчас!</h1>
        <div class="col-lg-4 text-center"></div>
        <div class="col-lg-4 text-center">
<p class="h4">Введите корректные данные для регистрации в чате!</p>
<?php if(isset($regError)):?>
    <br/><p class="h3 has-error text-center"><?php html_out($regError);?></p><br/>
<?php endif; ?>
<form role="form" class="form-group" action="?" method="post">
    <div>
        <label for="name">Введите Ваше имя: </label>
            <input class="form-control" placeholder="Введите желаемое имя" type="text" name="name" id="name" required>
    </div>
    <div>
        <label for="password">Введите пароль: </label>
        <input class="form-control" type="password" placeholder="Введите пароль" name="password" id="password" required >
    </div>
    <div>
        <br>
        <input type="hidden" name="action" value="registr">
        <input class="btn btn-primary" type="submit" value="Регистрация">
    </div>
</form>
            </div>
        <div class="col-lg-4 text-center"></div>
    </div>
<br/>
<br/>
<div class="row text-center h4"><a href="/index.php">На главную!</a></div>
</div>
</body>
</html>