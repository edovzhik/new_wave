<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>
        New Wave - Corporate Messaging
    </title>
    <link type="text/css" rel="stylesheet" href="assets/css/default_theme.css"/>
</head>
<body>
<div class="header">
    <div class="logo"></div>
    <?php
    if (isset($current_user)) {
        echo '<div class="account_panel">';
        echo '<div class="username">';
        echo $current_user->getName();;
        echo '</div>';
        echo '<div class="log_out">';
        echo '<form method="post" action="./">';
        echo '<input type="hidden" name="action" value="sign_out"/>';
        echo '<input type="submit" value="Sign Out"/>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>
<div class="content">