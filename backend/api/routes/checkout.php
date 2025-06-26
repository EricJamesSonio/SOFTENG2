<?php
require_once __DIR__ . '/../controllers/checkoutController.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    handleCheckout($con);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
