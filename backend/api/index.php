<?php
require_once dirname(__DIR__, 2) . '/database/db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get and parse the request path
$request = $_SERVER['REQUEST_URI'];
$uri = parse_url($request, PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Example: ["SOFTENG", "backend", "api", "index.php", "items"]
$route = isset($uri[4]) ? $uri[4] : '';

switch ($route) {
    case 'items':
        require __DIR__ . '/routes/items.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
}
