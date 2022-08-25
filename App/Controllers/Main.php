<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Balance;
use \App\Controllers\BalanceMenager;

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
        View::renderTemplate('Main/menu.html', [
            'user' => Auth::getUser()
        ]);
    }

        /**
     * Show the incomes page
     *
     * @return void
     */
    public function incomesAction()
    {
        View::renderTemplate('Main/incomes.html');
    }

            /**
     * Show the expenses page
     *
     * @return void
     */
    public function expensesAction()
    {
        View::renderTemplate('Main/expenses.html');
    }

                /**
     * Show the expenses page
     *
     * @return void
     */
    public function balanceAction()
    {
        // View::renderTemplate('Main/balance.html'); 
        $dateObject = (object) array('dataChoice' => "Bieżący miesiąc");
        $balance = new Balance($dateObject);
        BalanceMenager::showBalanceAction($balance);
    }   

}

