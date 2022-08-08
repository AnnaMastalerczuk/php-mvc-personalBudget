<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }

        /**
     * Login
     *
     * @return void
     */

    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        if ($user) {

            session_regenerate_id(true); // zmiana cookie

            $_SESSION['user_id'] = $user->id;

            $this->redirect('/main/menu');

        } else {

            View::renderTemplate('Home/index.html', [
                'email' => $_POST['email'],
                'error' => 'Niepoprawny login lub hasło',
            ]);
        }
    }

        /**
     * Log out a user
     *
     * @return void
     */
    public function destroyAction()
    {
        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Finally destroy the session
        session_destroy();

        $this->redirect('/');
    }


}
