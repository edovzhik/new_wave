<?php
require_once('controllers/auth.class.php');
require_once('models/department.class.php');
require_once('models/employee.class.php');
require_once('models/message.class.php');

abstract class Messenger
{
    public static function showHome()
    {
        $current_user = Employee::withId($_COOKIE['id']);
        $current_department = Department::withId($current_user->getDepartmentId());
        $departments = Department::getAllDepartments();
        $contacts = Employee::getAllEmployeesExceptCurrent();
        require('views/templates/header.tpl.php');
        require('views/messenger.html.php');
        require('views/templates/footer.tpl.php');
    }
}