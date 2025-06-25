<?php 
require_once '../models/Item.php';

function getAllItems() {
    $items = Item::getAll();
    header('Content-Type: application/json');
    echo json_encode($items);
}

function insertItem() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $category_id = $_POST['category_id'] ?? '';
        $subcategory_id = $_POST['subcategory_id'] ?? '';
        $description = $_POST['description'] ?? null;

        // Basic validation (you can expand this)
        if (empty($name) || empty($price) || empty($category_id) || empty($subcategory_id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields.']);
            return;
        }

        $success = Item::insert($name, $price, $category_id, $subcategory_id, $description);

        if ($success) {
            echo json_encode(['message' => 'Item inserted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to insert item.']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Only POST method allowed.']);
    }
}


?>