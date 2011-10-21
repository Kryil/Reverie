<?php
// This is a command line front controller for Reverie framework.

// Define required variables:

// Root path of the application
define('REVERIE_ROOT', dirname(__FILE__) . '/');
// Application namespace
define('APP', 'app');

// Bootstrap the framework
require REVERIE_ROOT.'reverie/bootstrap.php';

// Hack the request variable in the CLI environment
$req = getenv('REQUEST');
if (!empty($req))
    $_REQUEST = reverie\parse_str($req);

// Initialize the router using command line arguments
$router = new reverie\Router(implode('/', array_slice($argv, 1)));

// Create controller.
$controller = $router->instantiate();

// Execute the request
$response = $controller->execute($router);

// Output the response
echo $response;
