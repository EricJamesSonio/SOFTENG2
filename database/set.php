<?php
require_once "db.php";
require_once "scripts/function.php";

// Include table creation files in proper order
require_once "model/category.php";
require_once "model/subcategory.php";
require_once "model/starbucksitem.php";
require_once "model/attributes_templates.php";
require_once "model/itemattributes.php"; 

require_once "scripts/data/category_data.php";
require_once "scripts/data/subcategory_data.php";
require_once "scripts/data/attributes_templates_data.php";

echo "✅ All tables created successfully.";
?>
