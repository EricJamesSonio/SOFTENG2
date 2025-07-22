<?php
// Prevent HTML output in errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('html_errors', 0); // Very important for JSON response

// Safely start session only if none is active


// Always return JSON
header("Content-Type: application/json");

require_once dirname(__DIR__, 2) . '/model/Order.php';
require_once dirname(__DIR__, 3) . '/database/db2.php';

function handleCheckout($con) {
    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'] ?? [];
    $discount = $data['discount'] ?? 0;

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["message" => "Not logged in"]);
        return;
    }

    // Check if there are items in the order
    if (empty($items)) {
        http_response_code(400);
        echo json_encode(["message" => "No items in order"]);
        return;
    }

    $userId = $_SESSION['user_id'];
    $orderModel = new Order($con);
    $orderId = $orderModel->saveOrder($userId, $items, $discount);

    if ($orderId) {
        echo json_encode(["message" => "Checkout successful!"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Something went wrong saving the order."]);
    }
}
