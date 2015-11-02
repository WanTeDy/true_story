<?php session_start();?>
<select class="form-control" id="users_on" form="add_form" name="users[]" multiple  required size='2'>
<?php foreach($res_user_select as $row) :?>
    <?php if($_SESSION['name'] == $row['user']):?>
        <option class="form-control" disabled><?php html_out($row['user'])?></option>
        <?php else: ?>
        <option class="form-control" value="<?php html_out($row['user'])?>"><?php html_out($row['user'])?></option>
    <?php endif; ?>
<?php endforeach;?>
</select>