<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Expense extends \Core\Model
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

       /**
     * Select id from incomes category assigned to user
     *
     * @return int  id 
     */
    public function selectExpenseCategoryId()
    {
        $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE user_id = :userId AND name = :selectCategory';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':selectCategory', $this->category, PDO::PARAM_STR);
        $stmt->execute();

        $expenseArray = $stmt->fetch();
        $expenseCategoryId = $expenseArray['id'];

        return $expenseCategoryId;
    }

           /**
     * Select id from payment category assigned to user
     *
     * @return int  id 
     */
    public function selectPaymentCategoryId()
    {
        $sql = 'SELECT id FROM payment_methods_assigned_to_users WHERE user_id = :userId AND name = :selectPayment';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':selectPayment', $this->payment, PDO::PARAM_STR);
        $stmt->execute();

        $expenseArray = $stmt->fetch();
        $expenseCategoryId = $expenseArray['id'];

        return $expenseCategoryId;
    }

    /**
     * Save the expense model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $expenseCategoryId = $this->category;
            $paymentCategoryId = $this->payment;

            $sql = 'INSERT INTO expenses VALUES (NULL, :userId, :expenseId, :paymentId, :amount, :dateExpense, :comment)';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':expenseId', $expenseCategoryId, PDO::PARAM_INT);
            $stmt->bindValue(':paymentId', $paymentCategoryId, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':dateExpense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);            
                                          
            return $stmt->execute();
           
        }
        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        if ((strlen($this->comment) > 1000))
        {
            $this->errors[] = "Komentarz może zawierać do 1000 znaków";
        }

        if (ctype_alnum($this->comment) == false && $this->comment != "")
        {
            $this->errors[] = "Komentarz może składać się tylko z liter i cyfr (bez polskich znaków)";
        }

        if ($this->amount > 1000000) 
        {
            $this->errors[] = 'Podana kwota musi być mniejsza niż 1 000 000 zł';
        }
    }

//////////////////////////////////////////////////////////////////////// test ////////////////////////////////////////

    public static function getExpenseCategory()
    {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $expenseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        return $expenseArray;
    }


    public static function getPaymentCategory()
    {
        $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $paymentArray = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        return $paymentArray;
    }


    public static function getExpenseCategoryId($id)
    {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId AND id = :cat_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':cat_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $expenseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        return $expenseArray;
    }

/////////////////////////////////////////////////////////////////////

public static function getLimit($id)
{
    $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId AND id = :cat_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $expenseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $expenseArray;

}

public static function getExpensesMonth($id, $date, $dates)
{
    $startDate = $dates[0];
    $endDate = $dates[1];

    $sql = 'SELECT exd.name, ex.amount, ex.date_of_expense 
            FROM expenses ex, expenses_category_assigned_to_users exd 
            WHERE ex.user_id=:userId AND ex.expense_category_assigned_to_user_id=:id AND ex.date_of_expense>=:startDate AND ex.date_of_expense<=:endDate AND ex.user_id=exd.user_id AND ex.expense_category_assigned_to_user_id = exd.id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->execute();

    $expenseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $expenseArray;

}

public static function findStartAndEndDate($date)
    {
        $dateExplode = explode("-", $date);
        list($year, $month, $day) = $dateExplode;

        $givenMonthDaysNumber = date('t', strtotime($month . '/1'));

        $endDate = date("Y-m-d", mktime (0,0,0,$month,$givenMonthDaysNumber,$year));
        $startDate = date("Y-m-d", mktime (0,0,0,$month,'01',$year));
    
        $dates = [$startDate, $endDate];

        return $dates;
    }

public static function postLimitToBase($categoryId, $categoryLimit)
{
    $sql = 'UPDATE expenses_category_assigned_to_users SET userLimit = :cat_limit WHERE user_id = :userId AND id = :cat_id';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':cat_limit', $categoryLimit, PDO::PARAM_INT);
    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);          
                                    
    return $stmt->execute();

}

public static function getExpensesFromCategory($id)
{
    $sql = 'SELECT * FROM expenses WHERE user_id = :userId AND expense_category_assigned_to_user_id = :cat_id';
    // $sql = 'SELECT COUNT(id) FROM expenses WHERE user_id = :userId AND expense_category_assigned_to_user_id = :cat_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $expenseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $expenseArray;

}

public static function deleteExpensesInCategory($categoryId)
{
    $sql = 'DELETE FROM expenses WHERE user_id = :userId AND expense_category_assigned_to_user_id = :cat_id';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);          
                                    
    return $stmt->execute();
}

public static function deleteCategory($categoryId)
{
    $sql = 'DELETE FROM expenses_category_assigned_to_users WHERE user_id = :userId AND id = :cat_id';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);          
                                    
    return $stmt->execute();
}


}