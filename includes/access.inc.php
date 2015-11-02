<?php
function userIsLoggedIn(&$loginError='') {
    if (isset($_POST['action']) and $_POST['action'] == 'login') {
        if (!isset($_POST['name']) or $_POST['name'] == '' or
            !isset($_POST['password']) or $_POST['password'] == '') {
            $loginError = 'Пожалуйста заполните оба поля!';
            return FALSE;
        }
        $password = md5($_POST['password'] . $_POST['name']);
        if (databaseContainsAuthor($_POST['name'], $password)) {
            session_start();
            $_SESSION['loggedIn'] = TRUE;
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['password'] = $password;
            return TRUE;
        }
        else {
            session_start();
            unset($_SESSION['loggedIn']);
            unset($_SESSION['name']);
            unset($_SESSION['password']);
            unset($_SESSION['user_id']);
            unset($_SESSION['privelege']);
            $loginError = 'Неверное имя пользователя или пароль.';
            return FALSE;
        }
    }
    if (isset($_POST['action']) and $_POST['action'] == 'logout') {
        session_start();
        unset($_SESSION['loggedIn']);
        unset($_SESSION['name']);
        unset($_SESSION['password']);
        unset($_SESSION['user_id']);
        unset($_SESSION['privelege']);
        header('Location: /index.php');
        exit();
    }
    session_start();
    if (isset($_SESSION['loggedIn'])) {
        return databaseContainsAuthor($_SESSION['name'], $_SESSION['password']);
    }
return FALSE;
}
function databaseContainsAuthor($name, $password)
{
    include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
    try {
        $sql = 'SELECT COUNT(*) FROM users
        WHERE name = :name AND password = :password';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $name);
        $s->bindParam(':password', $password);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Невозможно найти пользователя, попробуйте пожалуйста позже!';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }
    $row = $s->fetch();
    if ($row[0] > 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}