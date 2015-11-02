<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cfg.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';
if ($_GET['registration'] == 1) {
    include $_SERVER['DOCUMENT_ROOT'] . '/reg_log/reg.html.php';
    exit();
}
$loginError='';
if (isset($_POST['action']) and $_POST['action'] == 'registr') {
    if (!isset($_POST['name']) or $_POST['name'] == '' or
        !isset($_POST['password']) or $_POST['password'] == '') {
        $regError = 'Пожалуйста заполните оба поля регистрации!';
        include $_SERVER['DOCUMENT_ROOT'] . '/reg_log/reg.html.php';
        exit();
    }
    if(iconv_strlen($_POST['password'], 'UTF-8') < 6) {
        $regError = 'Введите пароль не менее 6 символов!';
        include $_SERVER['DOCUMENT_ROOT'] . '/reg_log/reg.html.php';
        exit();
    }
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
    try {
        $sql = 'SELECT COUNT(*) FROM users WHERE
        name = :name';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $_POST['name']);
        $res = $s->execute();
        $row = $s->fetch();
        if ($row[0] > 0) {
            $regError = 'Имя занято! Выберите пожалуйста другое!';
            include $_SERVER['DOCUMENT_ROOT'] . '/reg_log/reg.html.php';
            exit();
        }
        $password = md5($_POST['password'] . $_POST['name']);
        $sql = 'INSERT INTO users SET
        name = :name, password = :password';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $_POST['name']);
        $s->bindParam(':password', $password);
        $s->execute();
        $loginError='Вы удачно зарегистрировались в системе, теперь можете авторизироваться!';
    }
    catch (PDOException $e) {
        $error = 'Не удалось зарегистрироваться в чате. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }
}
if (!userIsLoggedIn($loginError)) {
    include $_SERVER['DOCUMENT_ROOT'] . '/reg_log/login.html.php';
    exit();
}
header('Location: /chat/index.php');