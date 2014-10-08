<?php
require_once('models/employee.class.php');
require_once('models/invite.class.php');
require_once('helpers.php');

abstract class Auth
{
    private static $error;

    public static function showLoginPage()
    {
        require('views/templates/header.tpl.php');
        $error_message = self::$error;
        require('views/templates/error.tpl.php');
        require('views/auth.html.php');
        require('views/templates/footer.tpl.php');
    }

    public static function showRegistrationPage()
    {
        require('views/templates/header.tpl.php');
        $error_message = self::$error;
        require('views/templates/error.tpl.php');
        require('views/register.html.php');
        require('views/templates/footer.tpl.php');
    }

    public static function signIn()
    {
        $employee = Employee::withUsername($_POST['username']);
        if ($employee and $employee->getPassword() === md5($_POST['password'] . $employee->getSalt())) {
            setcookie('id', $employee->getId(), time() + 60 * 60 * 24);
            $_COOKIE['id'] = $employee->getId();
            setcookie('hash', $employee->updateSessionHash(), time() + 60 * 60 * 24);
            $_COOKIE['hash'] = $employee->getSessionHash();
            return true;
        } else {
            self::$error = 'Wrong Username or Password.';
            return false;
        }
    }

    public static function signOut()
    {
        if (Auth::isAuthenticated()) {
            $employee = Employee::withId($_COOKIE['id']);
            $employee->updateSessionHash();
            setcookie('id', '', time() - 1);
            setcookie('hash', '', time() - 1);
            if (Auth::isAuthenticated()) {
                return false;
            }
        }
        return true;
    }

    public static function isAuthenticated()
    {
        if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
            $employee = Employee::withId($_COOKIE['id']);
            if ($employee and $employee->getSessionHash() === $_COOKIE['hash']) {
                return true;
            }
        }
        return false;
    }

    public static function register()
    {
        $invite = Invite::withCode($_POST['invite_code']);
        if ($invite and $invite->isUsed() !== 1) {
            if (strlen($_POST['password']) > 8 and strlen($_POST['password']) < 20 and (preg_match("/^[a-zA-Z0-9!@#$%^&*()_+-=]*[0-9][a-zA-Z][a-zA-Z0-9!@#$%^&*()_+-=]*$/", $_POST['password']) or preg_match("/^[a-zA-Z0-9!@#$%^&*()_+-=]*[a-zA-Z][0-9][a-zA-Z0-9!@#$%^&*()_+-=]*$/", $_POST['password']))) {
                if ($_POST['password'] === $_POST['repeat_password']) {
                    if (strlen($_POST['username']) > 0 and strlen($_POST['username']) < 20 and preg_match("/^[a-zA-Z0-9]+$/", $_POST['username'])) {
                        $employee = Employee::withId($invite->getEmployeeId());
                        if ($employee and !Employee::withUsername($_POST['username'])) {
                            $employee->setUsername($_POST['username']);
                            $employee->setSalt(generateRandomString(16));
                            $employee->setPassword(md5($_POST['password'] . $employee->getSalt()));
                            $invite->markAsUsed();
                            setcookie('id', $employee->getId(), time() + 60 * 60 * 24);
                            $_COOKIE['id'] = $employee->getId();
                            setcookie('hash', $employee->updateSessionHash(), time() + 60 * 60 * 24);
                            $_COOKIE['hash'] = $employee->getSessionHash();
                            return true;
                        } else {
                            self::$error = 'This username is already taken.';
                        }
                    } else {
                        self::$error = 'Incorrect username. Username must be alphanumeric and be from 1 to 20 characters long.';
                    }
                } else {
                    self::$error = 'Passwords do not match.';
                }
            } else {
                self::$error = 'Password must be 8 to 20 characters long, contain at least 1 digit and 1 character and consist only of a-Z, 0-9 and symbols !@#$%^&*()_+-=.';
            }
        } else {
            self::$error = 'Wrong invite code.';
        }
        return false;
    }
}
