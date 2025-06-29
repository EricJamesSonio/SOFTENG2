<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once dirname(__DIR__, 2) . '/database/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


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


    default:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
}
