<?php
require_once 'controllers/auth.class.php';

class Application
{
    public function __construct()
    {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'sign_up':
                    if (!Auth::register() or !Auth::isAuthenticated()) {
                        Auth::showRegistrationPage();
                    } else {
                        echo 'Main page'; //home stub
                    }
                    break;
                case 'registration':
                    Auth::showRegistrationPage();
                    break;
                case 'sign_in':
                    if (!Auth::signIn() or !Auth::isAuthenticated()) {
                        Auth::showLoginPage();
                    } else {
                        echo 'Main page'; //home stub
                    }
                    break;
            }
        } else {
            if (Auth::isAuthenticated()) {
                echo 'Main page'; //home stub
            } else {
                setcookie('id', '', time() - 1);
                setcookie('hash', '', time() - 1);
                Auth::showLoginPage();
            }
        }
    }
}