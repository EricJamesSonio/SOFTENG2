<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// … the rest of your code …

//ini_set('display_errors', 0); // or 1 if you want to debug
//ini_set('html_errors', 0);    // ❗ important: prevents HTML in error


require_once dirname(__DIR__, 2) . '/database/db2.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get and parse the request path
$request = $_SERVER['REQUEST_URI'];
$uri = parse_url($request, PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

$route = isset($uri[4]) ? $uri[4] : '';

switch ($route) {
    case 'items':
        require __DIR__ . '/routes/items.php';
        break;

    case 'checkout':
        require __DIR__ . '/routes/checkout.php';
        break;

    case 'payment':
        require __DIR__ . '/routes/payment.php';
        break;

    case 'receipt':
        require __DIR__ . '/routes/receipt.php';
        break;

    case 'sizes':                  
        require __DIR__ . '/routes/sizes.php';
        break;

    case 'login':
        require __DIR__ . '/routes/login.php';
        break;

    case 'signup':
        require __DIR__ . '/routes/signup.php';
        break;



    default:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
}