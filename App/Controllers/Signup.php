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
class Signup extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

        /**
     * Sign up a new user
     *
     * @return void
     */

    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {

            $this->redirect('/signup/success');

        } else {
            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);

        }
        $user->save();
    }

        /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        // if(!Auth::isLoggedIn()){
        //     $this->redirect('/');
        // }
        $this->requireLogin();
        View::renderTemplate('Signup/success.html');
    }
}