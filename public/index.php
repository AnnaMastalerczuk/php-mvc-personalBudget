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
$router->add('{controller}/{action}');

// $router->add('/expenses', ['controller' => 'Expenses', 'action' => 'expenses']);

/////////////////////////////////////////////
$router->add('{controller}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('{controller}/{id:\d+}/{action}');
/////////////////////////////////////////////////////////
    
$router->dispatch($_SERVER['QUERY_STRING']);
