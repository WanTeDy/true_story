<form action="/index.php?" method="post" role="form" class="form-group">
    <div>
        <input type="hidden" name="action" value="logout">
        <label for="logout"><?php echo $_SESSION['name'];?>&nbsp;</label>
        <input type="submit" class="btn btn-danger" id="logout" value="Log out">
    </div>
</form>