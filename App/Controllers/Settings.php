<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;

use \App\Models\Set;
use \App\Models\Expense;


/**
 * Home controller
 *
 * PHP version 7.0
 */
class Settings extends Authenticated
{   

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $exCategory = Expense::getExpenseCategory();
        // print_r($exCategory);

        View::renderTemplate('Settings/index.html', [
            'exCat' => $exCategory
        ]);

    }

    public function getExpensesCategoryAction()
    {

        // echo json_encode(Expense::getCategory(), JSON_UNESCAPED_UNICODE);
        // echo json_encode("aasas");
        // $expense = new Expense($_POST);
        // $date = $expense->getExpenseCategory();
        $date = Expense::getExpenseCategory();
        echo json_encode($date);

        // return $date;
    }

    public function getExpensesCategoryIdAction()
    {

        // echo json_encode(Expense::getCategory(), JSON_UNESCAPED_UNICODE);
        // echo json_encode("aasas");
        // $expense = new Expense($_POST);
        // $date = $expense->getExpenseCategory();
        $id = $this->route_params['id'];


        $date = Expense::getExpenseCategoryId($id);
        echo json_encode($date);

        // return $date;
    }

}

