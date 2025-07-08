<?php
class Payment {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function saveReceipt($type, $paid, $total, $discount, $final) {
        $now = date('Y-m-d H:i:s');

        // Get the most recent order ID
        $result = $this->con->query("SELECT id FROM userorder ORDER BY id DESC LIMIT 1");
        $row = $result->fetch_assoc();
        $orderId = $row['id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'error' => 'No order found'];
        }

        // Calculate discount and change
        $discountAmount = $total - $final;
        $change = $paid - $final;

        // Insert into receipt table
        $stmt = $this->con->prepare("INSERT INTO receipt (
            order_id, discount_type, discount_value, discount_amount,
            final_amount, payment_amount, change_amount, issued_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            return ['success' => false, 'error' => 'Failed to prepare receipt insert'];
        }

        $stmt->bind_param("isddddds", $orderId, $type, $discount, $discountAmount, $final, $paid, $change, $now);

        if (!$stmt->execute()) {
            return ['success' => false, 'error' => 'Failed to insert receipt'];
        }

        // Generate receipt code
        $receiptId = $stmt->insert_id;
        $receiptCode = "RCPT-" . date('Ymd') . '-' . str_pad($receiptId, 4, '0', STR_PAD_LEFT);

        // Update receipt with receipt code
        $updateCode = $this->con->prepare("UPDATE receipt SET receipt_code = ? WHERE id = ?");
        if (!$updateCode) {
            return ['success' => false, 'error' => 'Failed to prepare receipt code update'];
        }
        $updateCode->bind_param('si', $receiptCode, $receiptId);
        $updateCode->execute();

        // Mark the order as completed
        $update = $this->con->prepare("UPDATE userorder SET status = 'completed', updated_at = ? WHERE id = ?");
        if (!$update) {
            return ['success' => false, 'error' => 'Failed to prepare order update'];
        }
        $update->bind_param("si", $now, $orderId);
        $success = $update->execute();

        // Deduct quantity from starbucksitem
        if ($success) {
            $this->deductItemQuantities($orderId);
            return [
                'success'     => true,
                'orderId'     => $orderId,
                'receiptId'   => $receiptId,
                'receiptCode' => $receiptCode
            ];
        }

        return ['success' => false, 'error' => 'Failed to finalize order'];
    }
    private function deductItemQuantities($orderId) {
    $query = "
        SELECT item_id, quantity
        FROM order_item
        WHERE order_id = ?
    ";
    $stmt = $this->con->prepare($query);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $itemId = $row['item_id'];
        $qty = $row['quantity'];

        $update = $this->con->prepare("
            UPDATE starbucksitem
            SET quantity = quantity - ?
            WHERE id = ?
        ");

        if (!$update) {
            error_log("❌ Failed to prepare update for item ID $itemId: " . $this->con->error);
            continue;
        }

        $update->bind_param("ii", $qty, $itemId);
        if (!$update->execute()) {
            error_log("❌ Failed to deduct $qty from item ID $itemId: " . $update->error);
        } else {
            error_log("✅ Deducted $qty from item ID $itemId");
        }
    }
}


}
?>
