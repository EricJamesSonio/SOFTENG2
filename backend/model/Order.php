<?php
class Order {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function saveOrder($items, $discount) {
    mysqli_begin_transaction($this->con);
    try {
        $userId = 1;             
        $now    = date('Y-m-d H:i:s');

        // 1) Total uses unit_price
        $totalAmount = 0;
        foreach ($items as $it) {
            $totalAmount += ($it['unit_price'] * $it['quantity']);
        }

        // 2) Insert userorder
        $stmt = $this->con->prepare("
          INSERT INTO userorder 
            (user_id, total_amount, status, placed_at, updated_at)
          VALUES (?, ?, 'pending', ?, ?)
        ");
        $stmt->bind_param("idds", $userId, $totalAmount, $now, $now);
        $stmt->execute();
        $orderId = $this->con->insert_id;

        // 3) Insert each order_item row with the correct payload keys
        $stmt2 = $this->con->prepare("
          INSERT INTO order_item
            (order_id, item_id, size_id, quantity, unit_price, total_price, created_at, updated_at)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($items as $it) {
            $unitPrice = $it['unit_price'];
            $qty       = $it['quantity'];
            $subtotal  = $unitPrice * $qty;

            $stmt2->bind_param(
              "iiiddsss",
              $orderId,
              $it['item_id'],    // use item_id, not id
              $it['size_id'],    // size_id from JS
              $qty,
              $unitPrice,
              $subtotal,
              $now,
              $now
            );
            $stmt2->execute();
        }

        mysqli_commit($this->con);
        return true;

    } catch (Exception $e) {
        mysqli_rollback($this->con);
        error_log("Checkout Error: " . $e->getMessage());
        return false;
    }
}


}

?>