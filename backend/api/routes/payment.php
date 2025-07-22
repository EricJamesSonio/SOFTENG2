
<?php
require_once __DIR__ . '/../controllers/paymentController.php';
require_once dirname(__DIR__, 3) . '/database/db2.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    handlePayment($con);
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
}
