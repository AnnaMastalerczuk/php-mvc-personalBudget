<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Income extends \Core\Model
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
    public function selectIncomesCategoryId()
    {
        $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :userId AND name = :checkbox';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':checkbox', $this->flexRadioDefault, PDO::PARAM_STR);
        $stmt->execute();

        $incomeArray = $stmt->fetch();
        $incomeCategoryId = $incomeArray['id'];

        return $incomeCategoryId;
    }

    /**
     * Save the income model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            // $incomeCategoryId = $this->selectIncomesCategoryId();
            $incomeCategoryId = $this->category;

            $sql = 'INSERT INTO incomes VALUES (NULL, :userId, :incomeId, :amount, :dateIncome, :comment)';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':incomeId', $incomeCategoryId, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':dateIncome', $this->date, PDO::PARAM_STR);
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

    public static function getIncomeCategory()
    {
        $sql = 'SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :userId';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $incomeArray = $stmt->fetchAll(PDO::FETCH_ASSOC);       

        return $incomeArray;
    }

    public static function getIncomesFromCategory($id)
{
    $sql = 'SELECT * FROM incomes WHERE user_id = :userId AND income_category_assigned_to_user_id = :cat_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $incomeArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $incomeArray;

}

public static function deleteIncomesInCategory($categoryId)
{
    $sql = 'DELETE FROM incomes WHERE user_id = :userId AND income_category_assigned_to_user_id = :cat_id';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);          
                                    
    return $stmt->execute();
}

public static function deleteCategory($categoryId)
{
    $sql = 'DELETE FROM incomes_category_assigned_to_users WHERE user_id = :userId AND id = :cat_id';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':cat_id', $categoryId, PDO::PARAM_INT);          
                                    
    return $stmt->execute();
}

public static function ifNewcategoryNameExists($newName)
{
    $sql = 'SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :userId AND name = :newName';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);

    // return $stmt->execute();

    $stmt->execute();

    $incomesArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $row = $stmt->rowCount();

    return $row;  

    // return $stmt->fetch() !== false;
}

public static function addNewCategory($newName)
{
    $sql = 'INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :userId, :newName)';
            
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
                                    
    return $stmt->execute();

}

}