<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1; dbname=chat_db', 'chat_admin', 'helloworld');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
}
catch (PDOException $e) {
    $error = 'К сожалению не удалось подключиться к базе данных. Попробуйте пожалуйста позже';
    include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
    exit();
}