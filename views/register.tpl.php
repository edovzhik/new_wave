<?php
include('header.tpl.php');
?>
<form method="post" action="./">
    <label>Invite code:</label>
    <input type="text" name="invite_code"/>
    <label>Desired username:</label>
    <input type="text" name="username"/>
    <label>Password:</label>
    <input type="password" name="password"/>
    <label>Repeat password:</label>
    <input type="password" name="repeat_password"/>
    <input type="hidden" name="action" value="register"/>
    <input type="submit" name="Register"/>
</form>
<?php
include('footer.tpl.php');
?>
