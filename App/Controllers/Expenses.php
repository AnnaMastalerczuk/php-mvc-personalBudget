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
        /**
     * Expense add
     * 
     * @return void
     */

    public function createAction()
    {
        $expense = new Expense($_POST);

        if ($expense->save()) {
           View::renderTemplate('Main/menu.html', [
            'info' => '(Wydatek dodany pomyślnie)',
        ]);

        } else {            
            View::renderTemplate('Main/expenses.html', [
                'expense' => $expense,
            ]);
        }  
    }

    //////////////////////////////////////////////// test //////////////////////////////////////////////////////////

    public function getExpensesCategoryAction()
    {

        // echo json_encode(Expense::getCategory(), JSON_UNESCAPED_UNICODE);
        // echo json_encode("aasas");
        $expense = new Expense($_POST);
        $date = $expense->getCategory();
        echo json_encode($date);

        // return $date;
    }

}