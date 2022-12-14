<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Balance extends \Core\Model
{

        /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

        /**
     * Class constructor
     *
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value){
            $this->$key = $value;
        };
    }


    public function showBalanceIncomes()
    {
        if($this->checkDate())
        {
            $dates = $this->checkDate();
            $startDate = $dates[0];
            $endDate = $dates[1];
            $sql = 'SELECT ind.name, inc.amount, inc.date_of_income, inc.income_comment 
            FROM incomes inc, incomes_category_assigned_to_users ind 
            WHERE inc.user_id=:userId AND inc.date_of_income>=:startDate AND inc.date_of_income<=:endDate AND inc.user_id=ind.user_id AND inc.income_category_assigned_to_user_id = ind.id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);           
            $stmt->execute();

            $incomes = $stmt->fetchAll();

            return $this->changeNameIncome($incomes);            
        }

        return false;  
    }

    public function incomesToChart()
    {
        if($this->showBalanceIncomes())
        {
            $dates = $this->checkDate();
            $startDate = $dates[0];
            $endDate = $dates[1];
            $sql = 'SELECT ind.name, SUM(inc.amount) AS sum 
            FROM incomes inc, incomes_category_assigned_to_users ind 
            WHERE inc.user_id=:userId AND inc.date_of_income>=:startDate AND inc.date_of_income<=:endDate AND inc.user_id=ind.user_id AND inc.income_category_assigned_to_user_id = ind.id GROUP BY ind.id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);           
            $stmt->execute();

            $incomes = $stmt->fetchAll();

            $incomes = $this->changeNameIncome($incomes);

            return $incomes;
        }
        return false;
    }

    public function expensesToChart()
    {
        if($this->showBalanceExpenses())
        {
            $dates = $this->checkDate();
            $startDate = $dates[0];
            $endDate = $dates[1];
            $sql = 'SELECT exd.name, SUM(ex.amount) AS sum 
            FROM expenses ex, expenses_category_assigned_to_users exd 
            WHERE ex.user_id=:userId AND ex.date_of_expense>=:startDate AND ex.date_of_expense<=:endDate AND ex.user_id=exd.user_id AND ex.expense_category_assigned_to_user_id = exd.id GROUP BY exd.id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);           
            $stmt->execute();

            $expenses = $stmt->fetchAll();
            $expenses = $this->changeNameExpense($expenses);

            return $expenses;
        }
        return false;
    }

    public function showBalanceExpenses()
    {
        if($this->checkDate())
        {
            $dates = $this->checkDate();
            $startDate = $dates[0];
            $endDate = $dates[1];
            $sql = 'SELECT exd.name, ex.amount, ex.date_of_expense, pay.name, ex.expense_comment 
            FROM expenses ex, expenses_category_assigned_to_users exd, payment_methods_assigned_to_users pay 
            WHERE ex.user_id=:userId AND ex.date_of_expense>=:startDate AND ex.date_of_expense<=:endDate AND ex.user_id=exd.user_id AND ex.user_id=pay.user_id AND ex.payment_method_assigned_to_user_id = pay.id AND ex.expense_category_assigned_to_user_id = exd.id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);           
            $stmt->execute();

            $expenses = $stmt->fetchAll();
            $expenses = $this->changeNameExpense($expenses);    
            $expenses = $this->changeNamePayment($expenses);

            return $expenses;
        }

        return false;  
    }


    public function checkDate()
    {
        $data = date("Y-m-d, H:i", mktime (0,0,0,10,15,1985));
        $year = date('Y');
        $month = date('m');
    
        $currentMonthDaysNumber = date('t', strtotime("MONTH"));
        $previousMonthDaysNumber = date('t', strtotime("-1 MONTH"));
    
        if(isset($this->dataChoice))
        {
            $dateChoice = $this->dataChoice;
    
            if($dateChoice == "Bie????cy miesi??c")
            {
                $endDate = date("Y-m-d", mktime (0,0,0,$month,$currentMonthDaysNumber,$year));
                $startDate = date("Y-m-d", mktime (0,0,0,$month,'01',$year));
             
            } 
                else if($dateChoice == "Poprzedni miesi??c")
            {
                $endDate = date("Y-m-d", mktime (0,0,0,($month-1),$previousMonthDaysNumber,$year));
                $startDate = date("Y-m-d", mktime (0,0,0,($month-1),'01',$year));  
    
            }
            else if ($dateChoice == "Bie????cy rok")
            {
                $endDate = date("Y-m-d", mktime (0,0,0,'12','31',$year));
                $startDate = date("Y-m-d", mktime (0,0,0,'01','01',$year));
    
            }          
        }
        else if(isset($this->startDate) && isset($this->endDate))
        {                           
            $startDate= $this->startDate;
            $endDate = $this->endDate;
            if ($startDate > $endDate){
                $this->errors[] = 'Podano nieprawid??owe daty';
                return NULL;
            }
        }
        else if (!isset($this->dataChoice)){
            $endDate = date("Y-m-d", mktime (0,0,0,$month,$currentMonthDaysNumber,$year));
            $startDate = date("Y-m-d", mktime (0,0,0,$month,'01',$year));
        }

        $dates = [$startDate, $endDate];

        return $dates;
    }    

    /**
     * Summary
     *
     * @return []  
     */
    public function summary($flows)
    {
        $sum = 0;

        foreach($flows as $flow)
        {
            $oneFlow = $flow[1];
            $sum += $oneFlow;            
        }

        $sum = sprintf("%01.2f", $sum);
        return $sum;
    }

    /**
     * Difference
     *
     * @return int  
     */
    public function difference($incomesSummary, $expensesSummary)
    {
        $difference = $incomesSummary - $expensesSummary;
        $difference = sprintf("%01.2f", $difference);
        return $difference;
    }

    /**
     * Change name
     *
     * @return []  
     */

    public function changeNameIncome($incomes)
    {
        foreach ($incomes as $key => $item) {

            $categoryName = $item["name"];
            switch($categoryName)
            {
                case "Salary": $categoryName = "Wyp??ata";
                break;
                case "Interest": $categoryName = "Odsetki";
                break;
                case "Allegro": $categoryName = "Allegro";
                break;
                case "Another": $categoryName = "Inne";
                break;
            }

            $item["name"] = $categoryName;
            $item["0"] = $categoryName;
            $incomes[$key] = $item;
        }

    return $incomes;
    }

    public function changeNameExpense($expenses)
    {
        foreach ($expenses as $key => $item) {

            $categoryName = $item["0"];
            switch($categoryName)
            {
                case "Transport": $categoryName = "Transport";
                break;
                case "Books": $categoryName = "Ksi????ki";
                break;
                case "Food": $categoryName = "Jedzenie";
                break;
                case "Apartments": $categoryName = "Mieszkanie";
                break;
                case "Telecommunication": $categoryName = "Telekomunikacja";
                break;
                case "Health": $categoryName = "Opieka zdrowotna";
                break;
                case "Clothes": $categoryName = "Ubrania";
                break;
                case "Hygiene": $categoryName = "Higiena";
                break;
                case "Kids": $categoryName = "Dzieci";
                break;
                case "Recreation": $categoryName = "Rozrywka";
                break;
                case "Trip": $categoryName = "Wycieczka";
                break;
                case "Savings": $categoryName = "Oszczedno??ci";
                break;
                case "For Retirement": $categoryName = "Emerytura";
                break;
                case "Debt Repayment": $categoryName = "Sp??ata d??ug??w";
                break;
                case "Gift": $categoryName = "Darowizna";
                break;
                case "Another": $categoryName = "Inne";
                break;
            }
            $item["0"] = $categoryName;
            $expenses[$key] = $item;
        }

    return $expenses;
    }

    public function changeNamePayment($expenses)
    {
        foreach ($expenses as $key => $item) {

            $categoryPay = $item["3"];
        
            switch($categoryPay)
            {
                case "Cash": $categoryPay = "Got??wka";
                break;
                case "Debit Card": $categoryPay = "Karta debetowa";
                break;
                case "Credit Card": $categoryPay = "Karta kredytowa";
                break;
            }

            $item["3"] = $categoryPay;
            $expenses[$key] = $item;
        }
    return $expenses;
    }       

}