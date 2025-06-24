<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');



// Subcategory Table (Espresso, Egg, Tea, etc.)
createTable($con, 'subcategory', "
    CREATE TABLE IF NOT EXISTS subcategory (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
    )
");



?>