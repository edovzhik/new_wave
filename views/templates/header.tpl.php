<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>
        New Wave - Corporate Messaging
    </title>
    <link type="text/css" rel="stylesheet" href="assets/css/default_theme.css"/>
    <script type="text/javascript" src="assets/javascript/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="assets/javascript/new_wave.js"></script>
</head>
<body>
<div class="header">
    <div class="logo"></div>
    <?php
    if (isset($current_user) and isset($current_department)) {
        echo '<div class="account_panel">';
        echo '<div class="user_details">';
        echo '<div class="username">';
        echo $current_user->getName();;
        echo '</div>';
        echo '<div class="title">';
        echo $current_user->getTitle();;
        echo '</div>';
        echo '<div class="department">';
        echo $current_department->getName();
        echo '</div>';
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