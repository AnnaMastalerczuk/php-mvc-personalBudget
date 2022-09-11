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

            $expenseCategoryId = $this->selectExpenseCategoryId();
            $paymentCategoryId = $this->selectPaymentCategoryId();

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

        if (ctype_alnum($this->comment) == false)
        {
            $this->errors[] = "Komentarz może składać się tylko z liter i cyfr (bez polskich znaków)";
        }

        if ($this->amount > 1000000) 
        {
            $this->errors[] = 'Podana kwota musi być mniejsza niż 1 000 000 zł';
        }
    }

}