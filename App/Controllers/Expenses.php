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

    public function getCategoryLimitAction()
    {

        $id = $this->route_params['id'];
        $date = Expense::getLimit($id);
        // print_r($date);
        echo json_encode($date);
    

        // echo json_encode(Expense::getCategory(), JSON_UNESCAPED_UNICODE);
        // echo json_encode("aasas");
        // $expense = new Expense($_POST);
        // $date = $expense->getLimit();
        // echo json_encode($date);

        // return $date;
    }

}