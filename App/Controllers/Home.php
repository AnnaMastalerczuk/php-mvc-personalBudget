<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

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

            Auth::login($user);
            $this->redirect('/main/menu');  
            // $this->redirect(Auth::getReturnToPage());

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
        Auth::logout();
        $this->redirect('/');
    }


}
