<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
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
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {

        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            // $sql = 'INSERT INTO users (id, username, password, email) VALUES (NULL, :login :password_hash, :email)';
            // $sql = 'INSERT INTO users VALUES (NULL, :login :password_hash, :email)';
            $sql = 'INSERT INTO users (username, password, email) VALUES (:username, :password, :email)';
                                              
            $db = static::getDB();
            $stmt = $db->prepare($sql);
                                                  
            $stmt->bindValue(':username', $this->login, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            
                                          
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
        $emailB = filter_var($this->email, FILTER_SANITIZE_EMAIL);

        if ((strlen($this->login) < 3) || (strlen($this->login) > 20))
        {
            $this->errors[] = "Login musi posiadać od 3 do 20 znaków";
        }

        if (ctype_alnum($this->login) == false)
        {
            $this->errors[] = "Login może składać się tylko z liter i cyfr (bez polskich znaków)";
        }

        if ($this->loginExists($this->login)) 
        {
            $this->errors[] = 'Istnieje login o takiej nazwie';
        }

        if ($this->findByEmail($this->email)) 
        {
            $this->errors[] = 'Istnieje konto przypisane do tego emaila';
        }

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $this->email))
        {
            $this->errors[] = "Podaj poprawny adres e-mail!";
        }

        if ((strlen($this->password) < 6) || (strlen($this->password) > 20))
        {
            $this->errors[] = "Hasło musi posiadać od 6 do 20 znaków";
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

 

}