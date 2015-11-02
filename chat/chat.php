<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/cfg.inc.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
include_once $_SERVER['DOCUMENT_ROOT']. '/includes/funcs.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/access.inc.php';
if (!userIsLoggedIn()) {
    header('Location: /index.php');
    exit();
}


//проверка пользователь онлайн или нет
if(isset($_SESSION['name'])) {
    $sess_id = session_id();
    try {
        $sql = 'SELECT * FROM online WHERE
        sid=:sess_id AND user=:name_u';
        $s = $pdo->prepare($sql);
        $s->bindParam(':sess_id', $sess_id);
        $s->bindParam(':name_u', $_SESSION['name']);
        $s->execute();
        $res=$s->fetch();
    } catch (PDOException $e) {
        $error = 'Не удалось проверить статус онлайн. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }
    if ($res > 0) {
       try {
            $sql = 'UPDATE online SET
            user=:name, puttime=NOW() WHERE
            sid=:sess_id';
            $s = $pdo->prepare($sql);
            $s->bindParam(':sess_id', $sess_id);
            $s->bindParam(':name', $_SESSION['name']);
            $s->execute();
        } catch (PDOException $e) {
            $error = 'Не удалось перезаписать статус онлайн. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
    } else {
        try {
            $sql = 'INSERT IGNORE INTO online SET
            sid=:sess_id, user=:name, puttime=NOW()';
            $s = $pdo->prepare($sql);
            $s->bindParam(':sess_id', $sess_id);
            $s->bindParam(':name', $_SESSION['name']);
            $s->execute();
        } catch (PDOException $e) {
            $error = 'Не удалось присвоить статус онлайн. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
    }

    //удаление неактивных пользователей
    try {
        $sql="DELETE FROM online
    WHERE puttime < NOW() -  INTERVAL '10' MINUTE";
        $s=$pdo->query($sql);
    } catch (PDOException $e) {
        $error = 'Не удалось обновить список пользователей онлайн. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }

    //проверка пользователь имеет привелегии админа или нет
    try {
        $sql = "SELECT * FROM users WHERE
        name=:name_u AND privelege <> '0'";
        $s = $pdo->prepare($sql);
        $s->bindParam(':name_u', $_SESSION['name']);
        $s->execute();
        $res = $s->fetch();
        if ($res > 0) {
            $_SESSION['privelege'] = true;
        }
        else {
            $_SESSION['privelege'] = false;
        }
    } catch (PDOException $e) {
        $error = 'Не удалось проверить привелегии. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }
}


//добавление нового сообщения от пользователя
if(isset($_POST['mess']) && $_POST['mess'] !="" && $_POST['mess'] !=" ") {
    $message = $_POST['mess'];
    $dialog_id = $_POST['dialog_id'];
    $name = $_SESSION['name'];
    try {
        $sql = 'INSERT INTO dialogs_message SET
        name = :name,
        dialog_id=:dialog_id,
        text=:text,
        date=NOW()';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $name);
        $s->bindParam(':dialog_id', $dialog_id);
        $s->bindParam(':text', $message);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Не удалось добавить сообщение. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }
}


//создание публичного канала админом
if (isset($_POST['dial_name']) && $_POST['dial_name'] !="" && $_POST['dial_name'] !=" ") {
    $dial_name = $_POST['dial_name'];
    //проверка существования диалога
    try {
        $sql = 'SELECT * FROM dialogs
	        WHERE dial_name=:name AND dial_type=\'public_d\'';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $dial_name);
        $s->execute();
        $priv = $s->fetch();
    } catch (PDOException $e) {
        $error = 'Не удалось найти информацию о приватном диалоге. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }

    //если диалог не создан, создаем
    if (!($priv[0] > 0)) {
        try {
            $sql = 'INSERT  INTO dialogs SET
        dial_name = :name, dial_type=\'public_d\'';
            $s = $pdo->prepare($sql);
            $s->bindParam(':name', $dial_name);
            $s->execute();
        } catch (PDOException $e) {
            $error = 'Не удалось добавить новый публичный канал. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
    }
}

//создание группового диалога
if (isset($_POST['dial_name_gr']) && $_POST['dial_name_gr'] != "" && $_POST['dial_name_gr'] !=" "
    && count($dial_users_gr = explode(',', $_POST['users'][0])) >0) {
    $dial_name_gr = $_POST['dial_name_gr'];

    if (count($dial_users_gr) == 1 && $dial_users_gr[0] == $_SESSION['user_name']) {
        $error = 'Нельзя добавлять самого себя!';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        die();
    }

    try {
        $sql = 'SELECT id FROM dialogs
	            WHERE dial_name=:name AND dial_type=\'group_d\'';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name', $dial_name_gr);
        $s->execute();
        $dial_gr = $s->fetch();
    } catch (PDOException $e) {
        $error = 'Не удалось найти информацию о групповом диалоге. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
        exit();
    }

    //если диалог не создан, создаем
    if (!($dial_gr[0] > 0)) {
        try {
            $sql = 'INSERT INTO dialogs SET
	                dial_name=:name, dial_type=\'group_d\'';
            $s = $pdo->prepare($sql);
            $s->bindParam(':name', $dial_name_gr);
            $s->execute();
            $last_id = $pdo->lastInsertId();
        } catch (PDOException $e) {
            $error = 'Не удалось создать групповой диалог. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
        try {
            $sql = 'INSERT IGNORE INTO dialogs_users
            SET dialog_id=:id, user_id=:user_id';
            $s = $pdo->prepare($sql);
            $s->bindParam(':id', $last_id);
            $s->bindParam(':user_id', $_SESSION['user_id']);
            $s->execute();
        } catch (PDOException $e) {
            $error = 'Не удалось привязать пользователей к групповому диалогу. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
        try {
            $pdo->beginTransaction();
            $sql1 = 'SELECT id FROM users
                     WHERE name=:uname';
            $s = $pdo->prepare($sql1);
            foreach ($dial_users_gr as $rows) {
                $s->bindParam(':uname', $rows);
                $s->execute();
                $users_id[]=$s->fetch();
            }
            $sql2 = 'INSERT IGNORE INTO dialogs_users SET dialog_id=:id,
                     user_id=:user_id';
            $s = $pdo->prepare($sql2);
            $s->bindParam(':id', $last_id);
            foreach ($users_id as $rows) {
                $s->bindParam(':user_id', $rows['id']);
                $s->execute();
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Не удалось привязать пользователей к групповому диалогу. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }

    } else {
        try {
            $pdo->beginTransaction();
            $sql1 = 'SELECT id FROM users
                     WHERE name=:uname';
            $s = $pdo->prepare($sql1);
            foreach ($dial_users_gr as $rows) {
                $s->bindParam(':uname', $rows);
                $s->execute();
                $users_id[]=$s->fetch();
            }
            $sql2 = 'INSERT IGNORE INTO dialogs_users SET dialog_id=:id,
                     user_id=:user_id';
            $s = $pdo->prepare($sql2);
            $s->bindParam(':id', $dial_gr['id']);
            foreach ($users_id as $rows) {
                $s->bindParam(':user_id', $rows['id']);
                $s->execute();
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Не удалось привязать новых пользователей к групповому диалогу. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }
    }
}



//удаление канала админом
if (isset($_POST['dialog_id']) && isset($_POST['delete']) && $_POST['delete'] == 'dialog' && $_SESSION['privelege'] === true) {
    try {
        $sql = 'DELETE dialogs, dialogs_message, dialogs_users FROM dialogs
        LEFT JOIN dialogs_message ON dialogs_message.dialog_id=dialogs.id
        LEFT JOIN dialogs_users ON dialogs_users.dialog_id=dialogs.id
        WHERE dialogs.id=:dialog_id';
        $s = $pdo->prepare($sql);
        $s->bindParam(':dialog_id', $_POST['dialog_id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Не удалось удалить публичный диалог. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }
    $error = 'Канал успешно удален!';
    include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
    die();
}


//выход из канала
if (isset($_GET['dialog_id']) && isset($_POST['action']) && $_POST['action'] == 'exit') {
    try {
        $sql = 'DELETE FROM dialogs_users WHERE
        dialog_id=:dialog_id AND user_id=:user_id';
        $s=$pdo->prepare($sql);
        $s->bindParam(':dialog_id', $_GET['dialog_id']);
        $s->bindParam(':user_id', $_SESSION['user_id']);
        $s->execute();
    } catch (PDOException $e) {
        $error = 'Не удалось удалить пользователя из диалога. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }
    header('Location: /index.php?');
    exit();
}


//обновление страницы чата
if($_POST['load'] == 'ok') {


    //определяем id пользователя
    if (!isset($_SESSION['user_id'])) {
        try {
            $sql = 'SELECT id FROM users
        WHERE name=:name';
            $s = $pdo->prepare($sql);
            $s->bindParam(':name', $_SESSION['name']);
            $s->execute();
            $user = $s->fetch();
        } catch (PDOException $e) {
            $error = 'Не удалось идентифицировать пользователя. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }
        $_SESSION['user_id'] = $user['id'];
    }



    //создание приватного диалога
    if (isset($_POST['user_name']) && $_POST['user_name'] != 'null' &&
        $_POST['user_name'] != '' && $_POST['user_name'] != ' ' && $_POST['user_name'] != $_SESSION['name']) {
        $name1=$_SESSION['name'] . '-' . $_POST['user_name'];
        $name2=$_POST['user_name'] . '-' . $_SESSION['name'];

        //проверка существования диалога
        try {
            $sql = 'SELECT * FROM dialogs
	        WHERE (dial_name=:name1 OR dial_name=:name2) AND dial_type=\'private_d\'';
            $s = $pdo->prepare($sql);
            $s->bindParam(':name1', $name1);
            $s->bindParam(':name2', $name2);
            $s->execute();
            $priv = $s->fetch();
        } catch (PDOException $e) {
            $error = 'Не удалось найти информацию о приватном диалоге. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
            exit();
        }

        //если диалог не создан, создаем
        if (!($priv[0] > 0)) {
            try {
                $sql = 'INSERT INTO dialogs SET
	            dial_name=:name1, dial_type=\'private_d\'';
                $s = $pdo->prepare($sql);
                $s->bindParam(':name1', $name1);
                $s->execute();
            } catch (PDOException $e) {
                $error = 'Не удалось привязать пользователя к приватному диалогу. Попробуйте позже.';
                include $_SERVER['DOCUMENT_ROOT'] . '/error.html.php';
                exit();
            }
        }
    }

    //извлечение списка публичных диалогов
    try {
        $sql = 'SELECT dial_name,id FROM dialogs
        WHERE dial_type=\'public_d\' ORDER BY dial_name';
        $s = $pdo->query($sql);
        $res_all = $s->fetchall();
    } catch (PDOException $e) {
        $error = 'Не удалось отобразить список каналов. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }

    //извлечение списка приватных диалогов
    $name_pr1 = $_SESSION['name'].'-%';
    $name_pr2 = '%-' . $_SESSION['name'];
    try {
        $sql = 'SELECT dial_name, id FROM dialogs
        WHERE dial_type=\'private_d\' AND ((dial_name LIKE :name1) OR (dial_name LIKE :name2))
        ORDER BY dial_name';
        $s = $pdo->prepare($sql);
        $s->bindParam(':name1', $name_pr1);
        $s->bindParam(':name2', $name_pr2);
        $s->execute();
        $res_dial = $s->fetchall();
    } catch (PDOException $e) {
        $error = 'Не удалось отобразить список приватных каналов. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }


    //извлечение списка групповых диалогов
    try {
        $sql = 'SELECT d.dial_name, d.id FROM dialogs d
        INNER JOIN dialogs_users du ON d.id=du.dialog_id
        WHERE du.user_id=:user_id AND dial_type=\'group_d\'  ORDER BY d.dial_name';
        $s = $pdo->prepare($sql);
        $s->bindParam(':user_id', $_SESSION['user_id']);
        $s->execute();
        $res_group = $s->fetchall();
    } catch (PDOException $e) {
        $error = 'Не удалось отобразить список групповых каналов. Попробуйте позже.';
        include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
        exit();
    }


    //админ может видеть все частные и групповые каналы, и заходить в них
    if($_SESSION['privelege'] === true) {
        try {
            $sql = 'SELECT dial_name, id FROM dialogs
        WHERE dial_type=\'private_d\'
        ORDER BY dial_name';
            $s = $pdo->query($sql);
            $s->execute();
            $res_dial = $s->fetchall();
        } catch (PDOException $e) {
            $error = 'Не удалось отобразить список приватных каналов. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }



        try {
            $sql = 'SELECT d.dial_name, d.id FROM dialogs d
        WHERE dial_type=\'group_d\' ORDER BY d.dial_name';
            $s = $pdo->query($sql);
            $s->execute();
            $res_group = $s->fetchall();
        } catch (PDOException $e) {
            $error = 'Не удалось отобразить список каналов. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }
    }


    $name_dial =' ';
    $res_mes=array();
    $res_user=array();
    //привязываем пользователя к текущему диалогу
    if (isset($_POST['dialog_id']) && $_POST['dialog_id'] != '' && $_POST['dialog_id'] != ' ' && $_POST['dialog_id'] != 0) {
        //проверка существования диалога и извлечения имени
        try {
            $sql = 'SELECT dial_name FROM dialogs
	        WHERE id=:dialog_id';
            $s = $pdo->prepare($sql);
            $s->bindParam(':dialog_id', $_POST['dialog_id']);
            $s->execute();
            $name_dial = $s->fetch();
        } catch (PDOException $e) {
            $error = 'Не удалось извлечь имя канала. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }
        if(is_null($name_dial['dial_name'])) {
            $error = 'Такого диалога не существует!';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }

        //привязываем пользователя к диалогу
        try {
            $sql = 'INSERT IGNORE INTO dialogs_users SET
            dialog_id=:dialog_id, user_id=:user_id';
            $s = $pdo->prepare($sql);
            $s->bindParam(':dialog_id', $_POST['dialog_id']);
            $s->bindParam(':user_id', $_SESSION['user_id']);
            $s->execute();
        } catch (PDOException $e) {
            $error = 'Не удалось привязать пользователя к диалогу. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }


        //извлечение списка сообщений чата
        try {
            $sql = 'SELECT name, text, date FROM dialogs_message ds
		INNER JOIN dialogs ON dialog_id=id
	WHERE dialog_id=:dialog_id ORDER BY date DESC
            LIMIT 0, 49';
            $s = $pdo->prepare($sql);
            $s->bindParam(':dialog_id', $_POST['dialog_id']);
            $s->execute();
            $res_mes = $s->fetchall();
        } catch (PDOException $e) {
            $error = 'Не удалось отобразить список сообщений чата. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }



        //извлечение списка онлайн пользователей
        try {
            $sql = 'SELECT u.name, u.id FROM users u
        INNER JOIN dialogs_users du ON du.user_id=u.id
        INNER JOIN online o ON o.user=u.name
        WHERE du.dialog_id=:dialog_id ORDER BY u.name';
            $s = $pdo->prepare($sql);
            $s->bindParam(':dialog_id', $_POST['dialog_id']);
            $s->execute();
            $res_user = $s->fetchall();
        } catch (PDOException $e) {
            $error = 'Не удалось отобразить список пользователей. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }
    }

    if($_POST['select'] == 'yes'){
        try {
            $sql = 'SELECT user FROM online
            ORDER BY user';
            $s = $pdo->query($sql);
            $s->execute();
            $res_user_select = $s->fetchall();
        } catch (PDOException $e) {
            $error = 'Не удалось отобразить список пользователей. Попробуйте позже.';
            include $_SERVER['DOCUMENT_ROOT'] .'/error.html.php';
            exit();
        }
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/chat.select.php';
    } else {
        include $_SERVER['DOCUMENT_ROOT'] . '/chat/chat.block.html.php';
    }
}