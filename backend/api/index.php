<?php
require_once 'database/db.php';

$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?'); 

switch (true) {
    case str_starts_with($request, '/items'):
        require 'routes/items.php';
        break;

    case str_starts_with($request, '/suppliers'):
        require 'routes/suppliers.php';
        break;

    case str_starts_with($request, '/ingredients'):
        require 'routes/ingredients.php';
        break;

    case str_starts_with($request, '/orders'):
        require 'routes/orders.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
}

?>
