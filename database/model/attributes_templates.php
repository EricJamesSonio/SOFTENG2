<?php

require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../scripts/function.php');

// This table holds definitions like 'Caffeine Level', 'Tea Level', etc.
createtable($con, 'attribute_templates', "
    CREATE TABLE IF NOT EXISTS attribute_templates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )
");
?>
