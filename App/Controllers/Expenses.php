<?php

namespace App\Controllers;

use \App\Models\Expense;
use \Core\View;

/**
 * Expense controller
 *
 * PHP version 7.0
 */
class Expenses extends Authenticated
{
    
    // /**
    //  * Show the index page
    //  *
    //  * @return void
    //  */
    // public function indexAction()
    // {
    //     View::renderTemplate('Home/index.html');
    // }

    //         /**
    //  * Show the incomes page
    //  *
    //  * @return void
    //  */
    // public function incomesAction()
    // {
    //     View::renderTemplate('Main/incomes.html');
    // }

        /**
     * Expense add
     * 
     * @return void
     */

    public function createAction()
    {
        $expense = new Expense($_POST);

        if ($expense->save()) {
        //    $this->redirect('/main/menu');

           View::renderTemplate('Main/menu.html', [
            'info' => '(Wydatek dodany pomyślnie)',
        ]);

        } else {            
            View::renderTemplate('Main/expenses.html', [
                'expense' => $expense,
            ]);
        }

  
    }
}