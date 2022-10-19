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

        View::renderTemplate('Settings/index.html', [
            'exCat' => $exCategory
        ]);

    }


    public function getExpensesCategoryAction()
    {
        $date = Expense::getExpenseCategory();
        echo json_encode($date);

    }

    public function getExpensesCategoryIdAction()
    {

        $id = $this->route_params['id'];

        $date = Expense::getExpenseCategoryId($id);
        echo json_encode($date);

    }    

}

