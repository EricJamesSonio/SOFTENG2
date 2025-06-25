<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

// table between Starbucks Items and Ingredients (CONNECTION)
createTable($con, 'item_ingredient', "
    CREATE TABLE item_ingredient (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        ingredient_id INT NOT NULL,
        quantity VARCHAR(50), -- e.g., '2 slices', '1 scoop'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(id) ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id) -- prevent duplicate ingredient entries for the same item
    )
");

?>
