<?php

namespace App\Controllers;

use \App\Models\User;
use \Core\View;

/**
 * User controller
 *
 * PHP version 7.0
 */
class UserMenager extends Authenticated
{    
       /**
     * 
     * 
     * @return void
     */

    public function changePasswordAction()
    {
        if(isset($_POST['pass'])) {
            $password = $_POST['pass'];
        }

        $result = User::changePassword($password);
        // $result = $password;

        echo json_encode($result);
    }

}