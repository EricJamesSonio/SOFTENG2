<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/scripts/function.php');

// Include table creation files in proper order
require_once(__DIR__ . '/model/category.php');
require_once(__DIR__ . '/model/subcategory.php');
require_once(__DIR__ . '/model/starbucksitem.php');
require_once(__DIR__ . '/model/attributes_templates.php');
require_once(__DIR__ . '/model/itemattributes.php'); 
require_once(__DIR__ . '/model/supplier.php');
require_once(__DIR__ . '/model/ingredients.php');
require_once(__DIR__ . '/model/supplier_contacts.php');

// Data population
require_once(__DIR__ . '/scripts/data/category_data.php');
require_once(__DIR__ . '/scripts/data/subcategory_data.php');
require_once(__DIR__ . '/scripts/data/attributes_templates_data.php');

echo "✅ All tables created successfully.";
?>
