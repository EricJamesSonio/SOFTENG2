<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

createTable($con, 'ingredient', "
    CREATE TABLE IF NOT EXISTS ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        quantity_in_stock DECIMAL(10, 2) DEFAULT 0,        -- Stock level in bulk unit (e.g., 5.00 kg)
        stock_unit VARCHAR(20),                            -- e.g., 'kg', 'L', 'pcs'
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE CASCADE
    )
");

?>
