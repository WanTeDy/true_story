<?php session_start();?>
<h1 class="h1 text-center"><?php html_out($name_dial['dial_name']); ?> </h1>
<br>
<div class="col-lg-3 text-left bac" style="background-color: #d9edf7; min-height: 500px; border: solid;">
    <h2 class="h2 text-center">Общие</h2>
   <div>
    <?php
    foreach($res_all as $row) :?>
        <?php if(isset($_SESSION['privelege']) && $_SESSION['privelege'] === true):?>
        <p style="font: message-box; font-size: medium">
            <a onclick="return confirm('Вы действительно хотите удалить диалог?')" href='?delete=dialog&dialog_id=<?php html_out($row['id']);?>'>
                <img style="width: 12px; height: 12px" src="/img/delete.png"></a>:&nbsp;
            <a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name'])?> </a>
        </p>
    <?php else: ?>
    <p style="font: message-box; font-size: medium"><a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name'])?> </a></p>
    <?php endif; ?>
    <?php endforeach; ?>
   </div>
    <h2 class="h2 text-center">Приватные</h2>
    <?php
    foreach($res_dial as $row) :?>
        <?php if(isset($_SESSION['privelege']) && $_SESSION['privelege'] === true):?>
            <p style="font: message-box; font-size: medium">
                <a onclick="return confirm('Вы действительно хотите удалить диалог?')" href='?delete=dialog&dialog_id=<?php html_out($row['id']);?>'>
                    <img style="width: 12px; height: 12px" src="/img/delete.png"></a>:&nbsp;
            <a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name']);?> </a>
            </p>
        <?php else: ?>
            <p style="font: message-box; font-size: medium"><a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name']);?> </a></p>
        <?php endif; ?>
    <?php endforeach; ?>
    <h2 class="h2 text-center">Групповые</h2>
    <?php
    foreach($res_group as $row) :?>
        <p style="font: message-box; font-size: medium"> <?php if(isset($_SESSION['privelege']) && $_SESSION['privelege'] === true):?>
            <p><a onclick="return confirm('Вы действительно хотите удалить диалог?')" href='?delete=dialog&dialog_id=<?php html_out($row['id']);?>'>
                    <img style="width: 12px; height: 12px" src="/img/delete.png"></a>:&nbsp;
            <a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name'])?> </a></p>
        <?php else: ?>
            <p style="font: message-box; font-size: medium"><a href='?dialog_id=<?php html_out($row['id'])?>'><?php html_out($row['dial_name'])?> </a></p>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<div id="chat_list" class="col-lg-6 text-left" style="background-color: #bce8f1; min-height: 500px; border: solid;">
    <h2 class="h2 text-center">Сообщения</h2>
    <?php
    foreach($res_mes as $row) :?>
        <p style="font: message-box; font-size: medium"><b style='color: green'>'<?php html_out($row['name'])?>':&nbsp;</b><?php html_out($row['text'])?></p>
    <?php endforeach; ?>
</div>
<div id="user_list" class="col-lg-3 text-left" style="background-color: #d9edf7; min-height: 500px; border: solid;">
    <h2 class="h2 text-center">Пользователи</h2>
    <?php
    foreach($res_user as $row) :?>
        <p style="font: message-box; font-size: medium"><a href='?user_name=<?php html_out($row['name'])?>'><?php html_out($row['name'])?></a></p>
    <?php endforeach;?>
</div>