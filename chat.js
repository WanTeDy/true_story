$(document).ready(function(){
    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
            return null;
        } else {
            return results[1] || 0;
        }
    }
    $('#btn_show').click(function(){
        $('#hide').toggle('fast', function() {
            $('#hide').html("<p><input placeholder='Введите название диалога' type='text' id='dial_name' required class='form-control'>"+
                "<input type='submit' class='btn btn-success btn-md' id='create_dial' value='OK'><p>");
        });
    });
    $('#admin').submit(function(){
        $.ajax({
            type: "POST",
            url: "chat.php",
            data: "dial_name="+$("#dial_name").val(),
            success: function(html){
                load_messes();
                $("#dial_name").val('');
                $('#show').toggle('fast', function() {
                });
            }
        });
        return false;
    });
    $('#add_pic').click(function(){
        load_users();
        $('#add_hide').toggle('fast', function() {
        });
    });
    $('#add_form').submit(function(){
        $.ajax({
            type: "POST",
            url: "chat.php",
            data: "dial_name_gr="+$("#dial_name_gr").val()+"&users[]="+$("#users_on").val(),
            success: function(html){
                load_messes();
                $("#dial_name_gr").val('');
                $('#add_hide').toggle('fast', function() {
                });
            }
        });
        return false;
    });
    $('#mes_form').submit(function(){
        $.ajax({
            type: "POST",
            url: "chat.php",
            data: "mess="+$("#input_mes").val()+"&dialog_id="+$.urlParam('dialog_id'),
            success: function(html){
                load_messes();
                $("#input_mes").val('');
            }
        });
        return false;
    });
    function load_messes() {
        $.ajax({
            type: "POST",
            url:  "chat.php",
            data: "load=ok&dialog_id="+$.urlParam('dialog_id')+"&user_name="+$.urlParam('user_name')
            +"&delete="+$.urlParam('delete')+"&add="+$.urlParam('add')+"&user_id="+$.urlParam('user_id'),
            success: function(html) {
                $("#chat").empty();
                $("#chat").append(html);
                $("#chat").scrollTop(9000);
            }
        });
    }
    function load_users() {
        $.ajax({
            type: "POST",
            url:  "chat.php",
            data: "load=ok&select=yes",
            success: function(html) {
                $("#users_add").empty();
                $("#users_add").append(html);
                $("#users_add").scrollTop(9000);
            }
        });
    }
    load_messes();
    setInterval(load_messes,1000);
    load_users();
});