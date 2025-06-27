<?php
require_once dirname(__DIR__, 2) . '/model/Order.php';

function handleCheckout($con) {
    $data = json_decode(file_get_contents('php://input'), true);

    $items = $data['items'] ?? [];
    $discount = $data['discount'] ?? 0;

    $orderModel = new Order($con);
    $success = $orderModel->saveOrder($items, $discount);

    if ($success) {
        echo json_encode(["message" => "Checkout successful!"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Something went wrong saving the order."]);
    }
}
