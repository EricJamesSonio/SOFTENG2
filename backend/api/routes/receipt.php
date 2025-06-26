<?php
require_once dirname(__DIR__, 2) . '/database/db.php';
require_once dirname(__DIR__, 2) . '/vendor/tcpdf/tcpdf.php'; // adjust path if needed

$orderId = $_GET['orderId'] ?? null;
if (!$orderId) {
    http_response_code(400);
    echo json_encode(["message" => "Missing orderId"]);
    exit;
}

// Fetch from DB
$sql = "
    SELECT r.*, u.placed_at
    FROM receipt r
    JOIN userorder u ON r.order_id = u.id
    WHERE r.order_id = ?
";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();

if (!$receipt) {
    http_response_code(404);
    echo json_encode(["message" => "Receipt not found"]);
    exit;
}

// Create PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 12);

$pdf->Write(0, "STARBUCKS RECEIPT\n\n");
$pdf->Write(0, "Receipt Code: {$receipt['receipt_code']}\n");
$pdf->Write(0, "Order ID: {$receipt['order_id']}\n");
$pdf->Write(0, "Discount Type: " . ucfirst($receipt['discount_type']) . "\n");
$pdf->Write(0, "Discount Value: {$receipt['discount_value']}%\n");
$pdf->Write(0, "Discount Amount: ₱" . number_format($receipt['discount_amount'], 2) . "\n");
$pdf->Write(0, "Final Amount: ₱" . number_format($receipt['final_amount'], 2) . "\n");
$pdf->Write(0, "Amount Paid: ₱" . number_format($receipt['payment_amount'], 2) . "\n");
$pdf->Write(0, "Change: ₱" . number_format($receipt['change_amount'], 2) . "\n");
$pdf->Write(0, "Issued At: {$receipt['issued_at']}\n");

$filename = "{$receipt['receipt_code']}.pdf";
$path = dirname(__DIR__, 2) . "/receipt/{$filename}";
$pdf->Output($path, 'F');

echo json_encode([
    "message" => "Receipt PDF created",
    "url" => "http://localhost/SOFTENG/receipt/{$filename}"
]);
