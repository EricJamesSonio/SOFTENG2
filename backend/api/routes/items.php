<?php
require_once __DIR__ . '/../controllers/ItemController.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getAllItems();
        break;

    case 'POST':
        insertItem();
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
