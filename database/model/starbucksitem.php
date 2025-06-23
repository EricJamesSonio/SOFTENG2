<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

// Starbucks Items Table
createtable($con, 'starbucksitem', "
    CREATE TABLE IF NOT EXISTS starbucksitem (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        category_id INT NOT NULL,
        subcategory_id INT NOT NULL,
        description TEXT,
        FOREIGN KEY (category_id) REFERENCES category(id),
        FOREIGN KEY (subcategory_id) REFERENCES subcategory(id)
    )
");



?>
