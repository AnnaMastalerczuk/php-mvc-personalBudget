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
     * Income add
     * 
     * @return void
     */

    public function createAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {
        //    $this->redirect('/main/menu');

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