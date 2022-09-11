<?php

namespace App\Controllers;

use \App\Models\Income;
use \Core\View;

/**
 * Incomes controller
 *
 * PHP version 7.0
 */
class Incomes extends Authenticated
{    
       /**
     * Income add
     * 
     * @return void
     */

    public function createAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {
           View::renderTemplate('Main/menu.html', [
            'info' => '(Przychód dodany pomyślnie)',
        ]);

        } else {            
            View::renderTemplate('Main/incomes.html', [
                'income' => $income,
            ]);
        }  
    }
}