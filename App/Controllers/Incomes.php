<?php

namespace App\Controllers;

use \App\Models\Income;
use \Core\View;

/**
 * Incomes controller
 *
 * PHP version 7.0
 */
class Incomes extends Authenticated
{    
       /**
     * Income add
     * 
     * @return void
     */

    public function createAction()
    {
        $income = new Income($_POST);

        if ($income->save()) {
           View::renderTemplate('Main/menu.html', [
            'info' => '(Przychód dodany pomyślnie)',
        ]);

        } else {            
            View::renderTemplate('Main/incomes.html', [
                'income' => $income,
            ]);
        }  
    }

    ////////////////////////////////////////////////////////////////

    public function getIncomesFromCategoryAction()
    {

        $id = $this->route_params['id'];
        $date = Income::getIncomesFromCategory($id);
        
        echo json_encode($date);
    }

    public function deleteIncomesInCategoryAction()
    {
        if(isset($_POST['deleteCategoryId'])) {
            $categoryId = $_POST['deleteCategoryId'];
        }

        $result = Income::deleteIncomesInCategory($categoryId);

        echo json_encode($result);
    }

    public function deleteCategoryAction()
    {
        if(isset($_POST['deleteCategoryId'])) {
            $categoryId = $_POST['deleteCategoryId'];
        }

        $result = Income::deleteCategory($categoryId);

        echo json_encode($result);
    }

    public function ifNewcategoryNameExistsAction()
    {

        $newName = $this->route_params['name'];
        $date = Income::ifNewcategoryNameExists($newName);
          
        echo json_encode($date);
    }

    public function addNewCategoryAction()
    {
        if(isset($_POST['name'])) {
            $categoryName = $_POST['name'];
        }

        $result = Income::addNewCategory($categoryName);

        echo json_encode($result);
    }
}