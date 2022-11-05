<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Sessions
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Home', 'action' => 'index']);
$router->add('logout', ['controller' => 'Home', 'action' => 'destroy']);
$router->add('menu', ['controller' => 'Main', 'action' => 'menu']);

$router->add('setting', ['controller' => 'Settings', 'action' => 'index']);

$router->add('expenses', ['controller' => 'Main', 'action' => 'expenses']);
$router->add('incomes', ['controller' => 'Main', 'action' => 'incomes']);

////////////////////////////////////////////////////
$router->add('expenses/getCategoryLimit/{id:[\d]+}', ['controller' => 'Expenses', 'action' => 'getCategoryLimit']);
$router->add('expenses/getExpensesDate/{id:[\d]+}/{date:(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])}', ['controller' => 'Expenses', 'action' => 'getExpensesDate']);
$router->add('expenses/postLimit', ['controller' => 'Expenses', 'action' => 'postLimit']);
$router->add('expenses/getExpensesFromCategory/{id:[\d]+}', ['controller' => 'Expenses', 'action' => 'getExpensesFromCategory']);
$router->add('expenses/deleteExpensesInCategory', ['controller' => 'Expenses', 'action' => 'deleteExpensesInCategory']);
$router->add('expenses/deleteCategory', ['controller' => 'Expenses', 'action' => 'deleteCategory']);
// $router->add('expenses/ifNewcategoryNameExists/{name:[\w]+}', ['controller' => 'Expenses', 'action' => 'ifNewcategoryNameExists']);
$router->add('expenses/ifNewcategoryNameExists/{name:[\w\sęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+}', ['controller' => 'Expenses', 'action' => 'ifNewcategoryNameExists']);
$router->add('expenses/addNewCategory', ['controller' => 'Expenses', 'action' => 'addNewCategory']);

$router->add('incomes/getIncomesFromCategory/{id:[\d]+}', ['controller' => 'Incomes', 'action' => 'getIncomesFromCategory']);
$router->add('incomes/deleteIncomesInCategory', ['controller' => 'Incomes', 'action' => 'deleteIncomesInCategory']);
$router->add('incomes/deleteCategory', ['controller' => 'Incomes', 'action' => 'deleteCategory']);
$router->add('incomes/ifNewcategoryNameExists/{name:[\w\sęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+}', ['controller' => 'Incomes', 'action' => 'ifNewcategoryNameExists']);
$router->add('incomes/addNewCategory', ['controller' => 'Incomes', 'action' => 'addNewCategory']);

$router->add('userMenager/changePassword', ['controller' => 'UserMenager', 'action' => 'changePassword']);



$router->add('{controller}/{action}');

// $router->add('/expenses', ['controller' => 'Expenses', 'action' => 'expenses']);

/////////////////////////////////////////////
// $router->add('{controller}/{action}');
// $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
// $router->add('{controller}/{id:\d+}/{action}');
/////////////////////////////////////////////////////////
    
$router->dispatch($_SERVER['QUERY_STRING']);
