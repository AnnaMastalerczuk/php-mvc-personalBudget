<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Main extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function menuAction()
    {
        View::renderTemplate('Main/menu.html');
    }


}