<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');



// Flexible Attributes Table (caffeine_level, cook_level, etc.)
createtable($con, 'item_attributes', "
    CREATE TABLE IF NOT EXISTS item_attributes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        attribute_name VARCHAR(50) NOT NULL,
        attribute_value VARCHAR(100) NOT NULL,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE
    )
");


?>