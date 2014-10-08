<?php
require_once('controllers/auth.class.php');
require_once('models/department.class.php');
require_once('models/employee.class.php');
require_once('models/message.class.php');

abstract class Messenger
{
    private static $error;

    public static function showHome()
    {
        $current_user = Employee::withId($_COOKIE['id']);
        $departments = Department::getAllDepartments();
        $contacts = Employee::getAllEmployeesExceptCurrent();
        require('views/templates/header.tpl.php');
        require('views/messenger.html.php');
        require('views/templates/footer.tpl.php');
    }

    public static function sendMessage()
    {
        if (isset($_COOKIE['id']) and isset($_POST['receiver_id']) and isset($_POST['is_departmental']) and isset($_POST['subject']) and isset($_POST['body'])) {
            if ($_POST['is_departmental'] === 1) {

            } else {

            }
        }
        return false;
    }
}