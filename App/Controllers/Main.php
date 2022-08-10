<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Main extends Authenticated
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function menuAction()
    {
        // if(!Auth::isLoggedIn()){
        //     Auth::rememberRequestedPage();

        //     $this->redirect('/');
        // }

        // $this->requireLogin();
        View::renderTemplate('Main/menu.html', [
            'user' => Auth::getUser()
        ]);
    }
}

