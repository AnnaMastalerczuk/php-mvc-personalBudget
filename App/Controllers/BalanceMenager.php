<?php

namespace App\Controllers;

use \App\Models\Balance;
use \Core\View;

/**
 * Expense controller
 *
 * PHP version 7.0
 */
class BalanceMenager extends Authenticated
{
    
        /**
     * Expense add
     * 
     * @return void
     */

    public function createAction()
    {
        $balance = new Balance($_POST);

        if ($balance->checkDate()) {

            $incomesBalance = $balance->showBalanceIncomes();
            $expensesBalance = $balance->showBalanceExpenses();
            $dates = $balance->checkDate();
            $incomesSummary = $balance->summary($incomesBalance);
            $expensesSummary = $balance->summary($expensesBalance);
            // echo $incomesSummary;
           
            // echo $incomesBalance.amount[0];
            // echo $incomesBalance.date_of_income[0];
           
            // View::renderTemplate('Main/balance.html',$incomesBalance);

        View::renderTemplate('Main/balance.html', [
            'incomesBalance' =>  $incomesBalance,
            'expensesBalance' =>  $expensesBalance,
            'dates' => $dates,
            'incomesSum' => $incomesSummary,
            'expensesSum' => $expensesSummary,
        ]);

            
        //    $this->redirect('/main/menu');

        //    View::renderTemplate('Main/menu.html', [
        //     'info' => '(Wydatek dodany pomyślnie)',
        // ]);

        // } else {            
        //     View::renderTemplate('Main/expenses.html', [
        //         'expense' => $expense,
        //     ]);
        } else echo 'blad';


  
    }
}