<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cfg.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/funcs.inc.php';
if (!userIsLoggedIn()) {
    header('Location: /index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Чат!</title>
    <script type="text/javascript" src="/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/chat.js"></script>
    <link rel="stylesheet" href="/bootstrap.min.css" type="text/css" media="all">
</head>
<body>
<div class="container">
<div class="row text-center" id="chat_block">
    <img src="/img/logo.png">
         <div class="col-lg-4 text-left" id="admin_show">
         <?php if(isset($_SESSION['privelege']) && $_SESSION['privelege'] === true):?>
            <form id="admin" class="form-group" role="form">
                  <input type="button" class="btn btn-warning" id="btn_show" name="show" value="Создать диалог">
                  <div id="show">
                        <div id="hide" style="display: none"></div>
                  </div>
             </form>
         <?php endif;?>
         </div>
    <div class="col-lg-4" id="dialog_list" >
        <div id="add">
            <div id="add_pic" class="btn-info" style="font-size: large">&nbsp;Создать группу:&nbsp;<a><img  style="width: 16px; height: 16px" src="/img/add.jpg"></a>&nbsp;&nbsp;</div>
            <form id="add_form" class="form-group" role="form">
                    <div id="add_hide" style="display: none">
                        <input type='text' placeholder="Введите имя группы" id="dial_name_gr" required class="form-control">
                        <div id="users_add"></div>
                        <input type='submit' class="btn btn-success btn-md" id='add_dial_gr' value='OK'>
                    </div>
            </form>
        </div>
    </div>
        <?php if(isset($_GET['dialog_id']) && is_numeric($_GET['dialog_id'])): ?>
    <div class="col-lg-4 text-right">
        <p><?php include $_SERVER['DOCUMENT_ROOT']. '/reg_log/logout.inc.html.php'; ?></p> <br>
        <form action="chat.php?dialog_id=<?php html_out($_GET['dialog_id']);?>" method="post" id="exit" class="form-group" role="form">
            <input type="hidden" name="action" value="exit">
            <input type="submit" class="btn btn-warning" value="Покинуть комнату">
        </form>
    </div>
    <br><br><br>
</div>
<div class="row text-center">
    <form id="mes_form" class="form-group text-center">
        <div>
            <table>
                <tr>
                    <td>
                        <input type="text" id="input_mes" size="70" maxlength="250" placeholder="Введите сообщение" class="form-control">
                    </td>
                    <td>
                        <label style="font-size: large; font-weight: bold" for id="send">&nbsp;&nbsp;&nbsp;<?php html_out($_SESSION['name']);?>&nbsp;&nbsp;&nbsp;</label>
                        <input type="submit" class="btn btn-primary" id="send" value="Отправить сообщение">
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>
    <?php else: ?>
    <div class="col-lg-4 text-right">
        <p><?php include $_SERVER['DOCUMENT_ROOT']. '/reg_log/logout.inc.html.php'; ?></p> <br>
    </div>
    <div class="row text-center">
    </div>
    <?php endif;?>
<div id="chat"></div>
</div>
</body>
</html>