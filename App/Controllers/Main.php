<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Models\Balance;
use \App\Models\Expense;
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
    public function incAction()
    {
        View::renderTemplate('Main/incomes.html');
    }

            /**
     * Show the expenses page
     *
     * @return void
     */
    public function expAction()
    {
        // View::renderTemplate('Main/expenses.html');

        $exCategory = Expense::getExpenseCategory();
        $payCategory = Expense::getPaymentCategory();
        // print_r($exCategory);

        View::renderTemplate('Main/expenses.html', [
            'exCat' => $exCategory,
            'payCat' => $payCategory,
        ]);

    }

                /**
     * Show the expenses page
     *
     * @return void
     */
    public function balanceAction()
    {
        $dateObject = (object) array('dataChoice' => "Bieżący miesiąc");
        $balance = new Balance($dateObject);
        BalanceMenager::showBalanceAction($balance);
    }   


    //             /**
    //  * Show the settings page
    //  *
    //  * @return void
    //  */
    // public function settingsAction()
    // {
    //     // View::renderTemplate('Settings/index.html');

    //     // $expensesCategory = Set::getExpenseCategory();


    // }

}

