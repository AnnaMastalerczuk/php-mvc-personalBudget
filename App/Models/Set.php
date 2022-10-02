<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Set extends \Core\Model
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


  

}