<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

createtable($con, 'ingredients', "
    CREATE TABLE IF NOT EXISTS ingredients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        supplier_id INT,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
    )
");

?>
