<?php
require_once dirname(__DIR__, 2) . '/database/db.php';


$request = $_SERVER['REQUEST_URI'];
$uri = parse_url($request, PHP_URL_PATH);
$uri = explode('/', $uri);

$route = isset($uri[2]) ? $uri[2] : '';

switch ($route) {
    case 'items':
        require __DIR__ . '/routes/item.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        break;
}
