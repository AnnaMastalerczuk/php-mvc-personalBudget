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

        // echo $_SESSION['user_id'];
        // echo $this->flexRadioDefault;

        $incomeArray = $stmt->fetch();
        $incomeCategoryId = $incomeArray['id'];
        // echo $incomeCategoryId;

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

            // $userId = $_SESSION['user_id'];

            $incomeCategoryId = $this->selectIncomesCategoryId();

            // $sql = 'INSERT INTO incomes VALUES (NULL, :userId, (SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :userId AND name = :checkbox), :amount, :dateIncome, :comment';
            // $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment) VALUES (:userId, :incomeId, :amount, :dateIncome, :comment)';
            $sql = 'INSERT INTO incomes VALUES (NULL, :userId, :incomeId, :amount, :dateIncome, :comment)';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);
                                        
            // $stmt->bindValue(':nullId', NULL, PDO::PARAM_NULL);
            // $stmt->bindValue(':id', NULL, PDO::PARAM_NULL);
            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':incomeId', $incomeCategoryId, PDO::PARAM_INT);
            // $stmt->bindValue(':checkbox', $this->flexRadioDefault, PDO::PARAM_STR);
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

        if ($this->amount > 1000000) 
        {
            $this->errors[] = 'Podana kwota musi być mniejsza niż 1 000 000 zł';
        }
    }






















     /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

        /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
  

         /**
     * See if a user record already exists with the specified login
     *
     * @param string $login login to search for
     *
     * @return boolean  True if a record already exists with the specified login false otherwise
     */
    protected function loginExists($login)
    {
        $sql = 'SELECT * FROM users WHERE username = :login';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch() !== false;
    }

     /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

       /**
     * Add category
     *
     * @return void  
     */
    public function addIncomesCategory()
    {        
        // $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT u.id, i.name FROM incomes_category_default AS i, users AS u WHERE u.email = :email';
        // && ($connection->query("INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT u.id, e.name FROM expenses_category_default AS e, users AS u WHERE u.username = '$login'"))
        // && ($connection->query("INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT u.id, p.name FROM payment_methods_default AS p, users AS u WHERE u.username = '$login'")))
                                              
            $db = static::getDB();
            $stmt = $db->prepare($sql);                                                  
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR); 
            $stmt->execute();           
    }

    public function addExpensesCategory()
    {        
        // $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
        $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT u.id, e.name FROM expenses_category_default AS e, users AS u WHERE u.email = :email';
        // && ($connection->query("INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT u.id, p.name FROM payment_methods_default AS p, users AS u WHERE u.username = '$login'")))
                                              
            $db = static::getDB();
            $stmt = $db->prepare($sql);                                                  
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR); 
            $stmt->execute();           
    }

    public function addPaymentCategory()
    {      
        $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT u.id, p.name FROM payment_methods_default AS p, users AS u WHERE u.email = :email';
                                              
            $db = static::getDB();
            $stmt = $db->prepare($sql);                                                  
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR); 
            $stmt->execute();           
    }
 

}