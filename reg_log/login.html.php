<?php
include_once $_SERVER['DOCUMENT_ROOT']. '/includes/funcs.inc.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Авторизация!</title>
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
<?php if (isset($loginError) && $loginError != ''):?>
    <p class="h3 text-center"><?php html_out($loginError);?></p>
<?php endif;?>
        <div class="col-lg-4 text-center"></div>
        <div class="col-lg-4 text-center">
            <form role="form" class="form-group" action="" method="post">
              <h3 class="panel-heading">Пожалуйста авторизируйтесь, чтобы войти в чат.</h3>
                 <div>
                    <label for="name">Введите Ваше имя: </label>
                    <input class="form-control" placeholder="Введите имя" type="text" name="name" id="name" required >
                  </div>
                  <div>
                    <label for="password">Введите пароль: </label>
                    <input class="form-control" placeholder="Введите пароль" type="password" name="password" id="password" required>
                  </div>
                  <div><br>
                      <input type="hidden" name="action" value="login">
                      <input class="btn btn-primary" type="submit" value="Авторизация">
                  </div>
            </form>
        </div>
    </div><br/><br/>
    <div class="row text-center h4">
        <a href="?registration=1">Зарегистрироваться</a>
    </div>
</div>
</body>
</html>