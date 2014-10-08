<?php
require_once('controllers/auth.class.php');
require_once('controllers/messenger.class.php');

class Application
{
    public function __construct()
    {
        if (Auth::isAuthenticated()) {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'sign_out':
                        if (Auth::signOut()) {
                            Auth::showLoginPage();
                        } else {
                            Messenger::showHome();
                        }
                        break;
                    default:
                        Messenger::showHome();
                }
            } else {
                Messenger::showHome();
            }
        } else {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'sign_in':
                        if (Auth::signIn()) {
                            Messenger::showHome();
                        } else {
                            Auth::showLoginPage();
                        }
                        break;
                    case 'sign_up':
                        if (Auth::register()) {
                            Messenger::showHome();
                        } else {
                            Auth::showRegistrationPage();
                        }
                        break;
                    case 'registration':
                        Auth::showRegistrationPage();
                        break;
                    default:
                        Auth::showLoginPage();
                }
            } else {
                Auth::showLoginPage();
            }
        }
    }
}