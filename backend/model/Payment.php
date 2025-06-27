<?php
class Payment {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function saveReceipt($type, $paid, $total, $discount, $final) {
        $now = date('Y-m-d H:i:s');

        $result = $this->con->query("SELECT id FROM userorder ORDER BY id DESC LIMIT 1");
        $row = $result->fetch_assoc();
        $orderId = $row['id'] ?? null;

        if (!$orderId) {
            return ['success' => false, 'error' => 'No order found'];
        }

        $stmt = $this->con->prepare("INSERT INTO receipt (
            order_id, discount_type, discount_value, discount_amount,
            final_amount, payment_amount, change_amount, issued_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            return ['success' => false, 'error' => 'Prepare insert failed'];
        }

        $discountAmount = $total - $final;
        $change = $paid - $final;

        $stmt->bind_param("isddddds", $orderId, $type, $discount, $discountAmount, $final, $paid, $change, $now);
        if (!$stmt->execute()) {
            return ['success' => false, 'error' => 'Execute insert failed'];
        }

        $receiptId = $stmt->insert_id;
        $receiptCode = "RCPT-" . date('Ymd') . '-' . str_pad($receiptId, 4, '0', STR_PAD_LEFT);

        $updateCode = $this->con->prepare("UPDATE receipt SET receipt_code = ? WHERE id = ?");
        if (!$updateCode) {
            return ['success' => false, 'error' => 'Prepare updateCode failed'];
        }
        $updateCode->bind_param('si', $receiptCode, $receiptId);
        $updateCode->execute();

        $update = $this->con->prepare("UPDATE userorder SET status = 'completed', updated_at = ? WHERE id = ?");
        if (!$update) {
            return ['success' => false, 'error' => 'Prepare update order failed'];
        }
        $update->bind_param("si", $now, $orderId);
        $success = $update->execute();

        if ($success) {
            $this->deductIngredients($orderId);
            return [
                'success'     => true,
                'orderId'     => $orderId,
                'receiptId'   => $receiptId,
                'receiptCode' => $receiptCode
            ];
        }

        return ['success' => false, 'error' => 'Failed to update order'];
    }

    private function deductIngredients($orderId) {
        $query = "
            SELECT oi.item_id, oi.quantity, ii.ingredient_id, ii.quantity_value
            FROM order_item oi
            JOIN item_ingredient ii ON oi.item_id = ii.item_id
            WHERE oi.order_id = ?
        ";
        $stmt = $this->con->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        $ingredientsToUpdate = [];

        while ($row = $result->fetch_assoc()) {
            $ingredientId = $row['ingredient_id'];
            $deductQty = $row['quantity'] * $row['quantity_value'];
            $ingredientsToUpdate[$ingredientId] = ($ingredientsToUpdate[$ingredientId] ?? 0) + $deductQty;
        }

        foreach ($ingredientsToUpdate as $id => $qty) {
            $updateStmt = $this->con->prepare("
                UPDATE ingredient
                SET quantity_in_stock = quantity_in_stock - ?
                WHERE id = ?
            ");
            $updateStmt->bind_param("di", $qty, $id);
            $updateStmt->execute();
        }
    }
}

?>