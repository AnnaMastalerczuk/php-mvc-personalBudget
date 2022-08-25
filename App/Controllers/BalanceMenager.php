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
            $this->showBalanceAction($balance);
        } else
        {
            View::renderTemplate('Main/balance.html', [
            'dateError' => 'Podano nieprawidłowe daty. Data końcowa nie może być wcześniejsza od początkowej',
        ]);
        }  
    }

    public static function showBalanceAction($balance)
    {
        $incomesBalance = $balance->showBalanceIncomes();
        $expensesBalance = $balance->showBalanceExpenses();
        $dates = $balance->checkDate();
        $incomesSummary = $balance->summary($incomesBalance);
        $expensesSummary = $balance->summary($expensesBalance);
        $incomesChart = $balance->incomesToChart();
        $expensesChart = $balance->expensesToChart();

    View::renderTemplate('Main/balance.html', [
        'incomesBalance' =>  $incomesBalance,
        'expensesBalance' =>  $expensesBalance,
        'dates' => $dates,
        'incomesSum' => $incomesSummary,
        'expensesSum' => $expensesSummary,
        'incomesChart' => $incomesChart,
        'expensesChart' => $expensesChart,
    ]);
    }

}