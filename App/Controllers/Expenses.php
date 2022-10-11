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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function getCategoryLimitAction()
    {

        $id = $this->route_params['id'];
        $date = Expense::getLimit($id);
        
        echo json_encode($date);
    }

    public function getExpensesDateAction()
    {

        $id = $this->route_params['id'];
        $date = $this->route_params['date'];

        $dates = Expense::findStartAndEndDate($date);
        $data = Expense::getExpensesMonth($id, $date, $dates);

        echo json_encode($data);

    }

    public function postLimitAction()
    {
        if(isset($_POST['postCategoryId'])) {
            $categoryId = $_POST['postCategoryId'];
        }

        if(isset($_POST['postCategoryLimit'])) {
            $categoryLimit = $_POST['postCategoryLimit'];
        }

        $result = Expense::postLimitToBase($categoryId, $categoryLimit);

        echo json_encode($result);
    }

}